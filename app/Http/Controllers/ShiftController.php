<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\UserLog;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Show open shift page.
     */
    public function openForm()
    {
        // Check if already has open shift
        $activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            // Show the close form instead
            return redirect()->route('shift.close');
        }

        return view('shift.open');
    }

    /**
     * Open a shift.
     */
    public function open(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        // Check if already has open shift
        $activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return response()->json([
                'error' => 'You already have an active shift'
            ], 422);
        }

        $shift = Shift::create([
            'user_id' => auth()->id(),
            'opened_at' => now(),
            'opening_balance' => $validated['opening_balance'],
            'status' => 'open',
        ]);

        UserLog::create([
            'user_id' => auth()->id(),
            'action' => 'shift_opened',
            'description' => "Shift opened with balance: Rp " . number_format($validated['opening_balance'], 0, ',', '.'),
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'shift_id' => $shift->id,
        ]);
    }

    /**
     * Show close shift page.
     */
    public function closeForm()
    {
        $activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            return redirect()->route('shift.open');
        }

        // Calculate expected total from transactions
        $expectedTotal = $activeShift->transactions()
            ->where('paid', true)
            ->where('void', false)
            ->sum('total_price');

        $shift = $activeShift->load('transactions');

        return view('shift.close', compact('shift', 'expectedTotal'));
    }

    /**
     * Close shift.
     */
    public function close(Request $request)
    {
        $validated = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $shift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return response()->json([
                'error' => 'No active shift found'
            ], 404);
        }

        $shift->closeShift($validated['closing_balance'], $validated['notes'] ?? null);

        UserLog::create([
            'user_id' => auth()->id(),
            'action' => 'shift_closed',
            'description' => "Shift closed. Expected: Rp " . number_format($shift->expected_total, 0, ',', '.') . 
                            ", Actual: Rp " . number_format($validated['closing_balance'], 0, ',', '.') .
                            ", Difference: Rp " . number_format($shift->difference, 0, ',', '.'),
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'shift' => $shift,
        ]);
    }
}
