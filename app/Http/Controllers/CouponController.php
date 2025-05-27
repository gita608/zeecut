<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $data['coupons'] = Coupon::latest()->get();
        $data['page_title'] = 'Coupon';
        $data['page_name'] = 'admin.coupons.index';

        return view('admin.main', $data);
    }

    public function create()
    {
        return view('admin.coupons.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|unique:coupons',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['status'] = 'active';

        Coupon::create($data);

        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully!');
    }

    // Delete coupon
    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->status = $coupon->status === 'active' ? 'inactive' : 'active';
        $coupon->save();

        return response()->json(['status' => true, 'new_status' => $coupon->status]);
    }


    /*
    // coupon application logic can be added here if needed

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

        if (!$coupon) {
            return back()->withErrors(['coupon_code' => 'Coupon not found.']);
        }

        if (!$coupon->isUsableByUser(auth()->id())) {
            return back()->withErrors(['coupon_code' => 'Coupon is not usable.']);
        }

        // Store coupon_id in session or proceed to use it in a payment
        session(['applied_coupon_id' => $coupon->id]);

        return back()->with('success', 'Coupon applied successfully.');
    }
    */


}