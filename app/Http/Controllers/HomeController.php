<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function privacy()
    {
        $data['page_title'] = 'Privacy';
        return view('frontend.privacy', $data);
    }
    public function contact()
    {
        $data['page_title'] = 'Contact';
        return view('frontend.contact', $data);
    }
    public function delete_account()
    {
        $data['page_title'] = 'Delete Account';
        return view('frontend.delete_account', $data);
    }

    public function sticker_print($order_id)
    {
        $order = Order::with(['order_items.product', 'user'])->find($order_id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->order_items->isEmpty()) {
            abort(400, 'No items in this order');
        }

        $data = [
            'order' => $order,
            'order_items' => $order->order_items
        ];


        $pdf = Pdf::loadView('admin.order.sticker', $data);

        return $pdf->stream('sticker.pdf');
    }
}
