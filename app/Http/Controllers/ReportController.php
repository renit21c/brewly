<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Daily sales report.
     */
    public function daily()
    {
        $today = today();
        
        $transactions = Transaction::whereDate('created_at', $today)
            ->where('paid', true)
            ->where('void', false)
            ->get();

        $totalRevenue = $transactions->sum('total_price');
        $totalTransactions = $transactions->count();
        $totalTax = $transactions->sum('tax');
        $totalDiscount = $transactions->sum('discount');

        // Payment breakdown
        $paymentBreakdown = Transaction::whereDate('created_at', $today)
            ->where('paid', true)
            ->where('void', false)
            ->with('payments.paymentMethod')
            ->get()
            ->flatMap(fn($t) => $t->payments)
            ->groupBy('paymentMethod.name')
            ->map(fn($items) => $items->sum('amount'));

        // Order type breakdown
        $orderTypeBreakdown = Transaction::whereDate('created_at', $today)
            ->where('paid', true)
            ->where('void', false)
            ->with('orderType')
            ->get()
            ->groupBy('orderType.name')
            ->map(fn($items) => [
                'count' => $items->count(),
                'amount' => $items->sum('total_price')
            ]);

        return view('admin.reports.daily', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalTax',
            'totalDiscount',
            'paymentBreakdown',
            'orderTypeBreakdown'
        ));
    }

    /**
     * Monthly sales report.
     */
    public function monthly(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $transactions = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('paid', true)
            ->where('void', false)
            ->get();

        $totalRevenue = $transactions->sum('total_price');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Daily breakdown
        $dailyBreakdown = $transactions->groupBy(function($t) {
            return $t->created_at->format('Y-m-d');
        })->map(fn($items) => [
            'count' => $items->count(),
            'amount' => $items->sum('total_price')
        ]);

        return view('admin.reports.monthly', compact(
            'year',
            'month',
            'totalRevenue',
            'totalTransactions',
            'averageTransaction',
            'dailyBreakdown'
        ));
    }

    /**
     * Menu analytics report.
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', 'week'); // week, month, all
        
        $query = Transaction::with('details.product')
            ->where('paid', true)
            ->where('void', false);

        if ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }

        $transactions = $query->get();

        // Top selling products
        $topProducts = $transactions->flatMap(fn($t) => $t->details)
            ->groupBy('product_id')
            ->map(fn($items) => [
                'product' => $items->first()->product,
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum('subtotal')
            ])
            ->sortByDesc('revenue')
            ->take(10);

        // Bottom selling products
        $bottomProducts = Product::all()
            ->map(function($product) use ($transactions) {
                $sold = $transactions->flatMap(fn($t) => $t->details)
                    ->where('product_id', $product->id)
                    ->sum('quantity');
                return [
                    'product' => $product,
                    'quantity' => $sold,
                    'revenue' => $sold * $product->price
                ];
            })
            ->sortBy('revenue')
            ->take(5)
            ->where('revenue', '>', 0);

        return view('admin.reports.analytics', compact(
            'period',
            'topProducts',
            'bottomProducts'
        ));
    }
}
