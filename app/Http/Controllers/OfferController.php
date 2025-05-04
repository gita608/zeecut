<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    protected $offer;
    public function __construct()
    {
        $this->offer = new Offer();
    }

    public function index()
    {
        $data['products'] = Product::get();
        $data['list_items'] = $this->offer->getJoin(
            joins: [
                ['products', 'offers.product_id', 'products.id']
            ],
            where: [], // Add conditions if needed
            select: ['offers.*', 'products.name as product_name', 'products.thumbnail'], // Select required fields
            order_by: ['offers.id' => 'DESC'] // Order by product ID in descending order
        );

        $data['page_title'] = 'Offer';
        $data['page_name'] = 'admin.offer.index';
        return view('admin.main', $data);
    }

    public function ajax_add()
    {
        $data['products'] = Product::get();
        return view('admin.offer.add', $data);
    }

    public function submit(Request $request)
    {

        // Validate the form data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            // 'start_date' => 'required|date|after_or_equal:today',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = [

            'product_id' => $request->product_id,
            'name' => $request->title,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        // Save the offer to the database
        Offer::create($data);
        // dd(DB::getQueryLog());
        return redirect(route('offer.index'))->with('message_success', 'Created Successfully!');
    }

    public function ajax_edit($id)
    {

        $data['products'] = Product::get();
        $data['edit_data'] = Offer::findOrFail($id);
        return view('admin.offer.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = [

            'product_id' => $request->product_id,
            'name' => $request->title,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $offer = Offer::findOrFail($id);
        $offer->update($data);
        return redirect(route('offer.index'))->with('message_success', 'Updated Successfully!');
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        return redirect(route('offer.index'))->with('message_success', 'Deleted Successfully!');
    }

    public function uploadFile($file, $folder = 'uploads', $disk = 'public')
    {
        // Check if the file is valid
        if ($file->isValid()) {
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $filePath = $file->storeAs($folder, $filename, $disk);
            return $filePath;  // Return the file path
        }
        return null;  // Return null if the file is not valid
    }
}