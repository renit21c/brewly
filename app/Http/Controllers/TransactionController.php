<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionPayment;
use App\Models\OrderType;
use App\Models\PaymentMethod;
use App\Models\Shift;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display the PoS interface.
     */
    public function index()
    {
        // Get active shift for current user
        $activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();
        
        // If no active shift, redirect to shift open page
        if (!$activeShift) {
            return redirect()->route('shift.open')->with('info', 'Please open a shift to start selling.');
        }

        $products = Product::where('stock', '>', 0)->get();
        $orderTypes = OrderType::all();
        $paymentMethods = PaymentMethod::where('active', true)->get();
        
        return view('pos.index', compact('products', 'orderTypes', 'paymentMethods', 'activeShift'));
    }

    /**
     * Process checkout with multiple payments, variants, and more.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variants' => 'nullable|array',
            'order_type_id' => 'required|exists:order_types,id',
            'customer_name' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payments' => 'required|array',
            'payments.*.payment_method_id' => 'required|exists:payment_methods,id',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.reference' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Validate stock
            $items = $validated['items'];
            $products = [];
            $totalPrice = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'error' => "Insufficient stock for {$product->name}"
                    ], 422);
                }

                $itemTotal = $product->price * $item['quantity'];
                
                // Add variant prices
                if (isset($item['variants']) && is_array($item['variants'])) {
                    // Variants akan di-calculate di frontend
                    // Di sini kita tinggal menerima total yang sudah benar
                }
                
                $totalPrice += $itemTotal;
                $products[$product->id] = [
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal,
                    'product' => $product,
                    'variants' => $item['variants'] ?? [],
                ];
            }

            // Calculate total with tax and service charge
            $subtotal = $validated['subtotal'] ?? $totalPrice;
            $tax = $validated['tax'] ?? 0;
            $serviceCharge = $validated['service_charge'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $finalTotal = $subtotal + $tax + $serviceCharge - $discount;

            // Validate payments
            $totalPaid = collect($validated['payments'])->sum('amount');
            if ($totalPaid < $finalTotal) {
                return response()->json([
                    'error' => 'Total payment is less than total price'
                ], 422);
            }

            // Create transaction
            $invoiceCode = 'INV-' . now()->format('YmdHis') . '-' . Str::random(4);
            $changeMoney = $totalPaid - $finalTotal;

            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'cashier_id' => auth()->id(),
                'order_type_id' => $validated['order_type_id'],
                'shift_id' => Shift::where('user_id', auth()->id())
                    ->where('status', 'open')->first()->id,
                'total_price' => $finalTotal,
                'tax' => $tax,
                'service_charge' => $serviceCharge,
                'discount' => $discount,
                'cash_paid' => $totalPaid,
                'change_money' => $changeMoney,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'paid' => true,
            ]);

            // Create transaction details
            foreach ($products as $productId => $data) {
                $detail = TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'quantity' => $data['quantity'],
                    'subtotal' => $data['subtotal'],
                ]);

                // Create variant records if any
                // if (!empty($data['variants'])) {
                //     foreach ($data['variants'] as $variantId) {
                //         TransactionDetailVariant::create([
                //             'transaction_detail_id' => $detail->id,
                //             'variant_option_id' => $variantId,
                //         ]);
                //     }
                // }

                // Deduct stock
                $data['product']->decrement('stock', $data['quantity']);
            }

            // Create payment records
            foreach ($validated['payments'] as $payment) {
                TransactionPayment::create([
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $payment['payment_method_id'],
                    'amount' => $payment['amount'],
                    'reference' => $payment['reference'] ?? null,
                ]);
            }

            // Log activity
            UserLog::create([
                'user_id' => auth()->id(),
                'action' => 'transaction_completed',
                'description' => "Transaction {$invoiceCode} completed",
                'ip_address' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'invoice_code' => $invoiceCode,
                'total_price' => $finalTotal,
                'change_money' => $changeMoney,
            ]);
        });
    }

    /**
     * Void a transaction item with reason.
     */
    public function voidItem(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'reason' => 'required|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        // Only cashier can void their own transaction, admin can void any
        if ($transaction->cashier_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Restore stock
        foreach ($transaction->details as $detail) {
            $detail->product->increment('stock', $detail->quantity);
        }

        // Mark as void
        $transaction->update([
            'void' => true,
            'void_reason' => $validated['reason'],
            'paid' => false,
        ]);

        UserLog::create([
            'user_id' => auth()->id(),
            'action' => 'void_item',
            'description' => "Transaction {$transaction->invoice_code} voided. Reason: {$validated['reason']}",
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['success' => true]);
    }
}
