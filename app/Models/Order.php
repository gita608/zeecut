<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'cart_id',
        'order_no',
        'total_amount',
        'status',
    ];

    public function order_items(){

        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function generate_order_number()
    {
        $lastOrder = $this->getData([], ['order_no'], ['id' => 'DESC'], 1)->first();

        if (isset($lastOrder)) {
            $lastOrderNo = $lastOrder->order_no;
            $prefix = 'ORD';
            $number = (int) str_replace($prefix, '', $lastOrderNo);
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }

        return 'ORD' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // e.g., ORD0001
    }

    public function get_order_status($data)
    {
        $result = [
            'placed'     => date('d-M-Y', strtotime($data->ordered_date)),
            'packed'     => $data->packed_date != null ? date('d-M-Y', strtotime($data->packed_date)) : '',
            'dispatched' => $data->dispatched_date != null ? date('d-M-Y', strtotime($data->dispatched_date)) : '',
            'delivered'  => $data->delivered_date != null ? date('d-M-Y', strtotime($data->delivered_date)) : '',
        ];
        return $result;
    }



}
