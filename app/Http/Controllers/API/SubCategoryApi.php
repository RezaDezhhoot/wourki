<?php

namespace App\Http\Controllers\API;

use App\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class SubCategoryApi extends Controller
{
    public function getAllSubcategory(Request $request)
    {
        $subCategories = SubCategory::where('category_id' , $request->categoryId)
            ->get();
        foreach ($subCategories as $index => $subCategory) {
            if($subCategory->icon){
                $subCategories[$index]->pic = URL::to('/icon') . '/' . $subCategory->icon;
            }
        }
        return response()->json($subCategories , 200);
    }
}
