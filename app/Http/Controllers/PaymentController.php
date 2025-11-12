<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Payment list
    public function index()
    {
        $data['payments'] = Payment::with(['user', 'order'])->latest()->get();

        $data['page_title'] = 'Payment';
        $data['page_name'] = 'admin.payments.index';

        return view('admin.main', $data);
    }

    public function addPaymentForm($paymentId)
    {
        $payment = Payment::with(['user', 'order'])->findOrFail($paymentId);
        return view('admin.payments.add', compact('payment'));
    }

    public function storePayment(Request $request, $paymentId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $payment = Payment::findOrFail($paymentId);

        DB::transaction(function () use ($payment, $request) {
            // Update main payment record
            $payment->paid += $request->amount;
            // $payment->pending_amount = max(0, $payment->pending_amount - $request->amount);

            // Calculate remaining balance
            $remaining = ($payment->pending_amount + $payment->pay_later_credit) - $payment->paid;

            // Update status
            if ($remaining <= 0) {
                $payment->status = 'completed';
                $payment->completed_date = now();
            } elseif ($payment->paid > 0) {
                $payment->status = 'partial';
            }

            $payment->paid_date = now();
            $payment->save();

            // Insert into payment history
            PaymentHistory::create([
                'payment_id' => $payment->id,
                'amount' => $request->amount,
                'paid_at' => now(),
            ]);
        });

        return redirect()->route('payments.index')->with('success', 'Payment updated and history recorded.');
    }

    public function history($paymentId)
    {
        $payment = Payment::with(['user', 'order', 'histories'])
                        ->findOrFail($paymentId);
        if ($payment->paid_date) {
            $payment->paid_date = \Carbon\Carbon::parse($payment->paid_date);
        }
        
        $histories = $payment->histories()
                          ->latest()
                          ->paginate(10);

        return view('admin.payments.history', [
            'payment' => $payment,
            'histories' => $histories
        ]);
    }
}
