<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends ApiBaseController
{
    protected $category;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->category = new Categories();
        
    }
    // ✅ Check Pincode Access
    public function index(Request $request)
    {
        $data = $this->category->getData();
        return $this->sendSuccessResponse($data, 'Success');
    }

}