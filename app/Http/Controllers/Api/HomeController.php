<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;

class HomeController extends ApiBaseController
{
    protected $category;
    protected $user;
    protected $banners;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->category = new Categories();
        $this->user = new User();
        $this->banners = new Banners();
        
    }
    
    public function index(Request $request)
    {
        $user = User::where('id', $this->userId)->first();
        $categories = $this->category->getData();
        foreach($categories as &$category){
            $category->icon = $category->icon ? asset('storage/' . $category->icon) : '';
        }
        $banners = $this->banners->getData();

        foreach($banners as &$banner){
            $banner->image = $banner->image ? asset('storage/' . $banner->image) : '';
        }

        $data = [
            'user_data' => $user->userdata(),
            'categories' => $categories,
            'banners' => $banners
        ];
        
        return $this->sendSuccessResponse($data, 'Success');
    }


}