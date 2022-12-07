<?php

namespace App\Http\Controllers\API;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class CategoryApi extends Controller
{
    public function getAllCat()
    {
        $categories = Category::all();
        foreach ($categories as $index => $category){
            if($category->icon){
                $categories[$index]->pic = URL::to('/icon') . '/' . $category->icon;
            }
        }
        return response()->json($categories , 200);
    }
}
