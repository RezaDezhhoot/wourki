<?php

namespace App\Http\Controllers;

use App\Categories;
use App\CategoriesOfGuild;
use App\Category;
use App\Comment;
use App\Http\Requests\web\saveNewProductRequest;
use App\Libraries\RetailerTabs;
use App\Libraries\Swal;
use App\ProductPhoto;
use App\Products;
use App\ProductSeller;
use App\Slider;
use App\SubCategory;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    public function checkProductQuantity(Request $request , $productId){
        $product = Products::find($productId);
        if($product == null || $product->quantity > 0){
            return 1;
        }else{
            return 0;
        }
    }

    public function deleteProductById(Request $request , Products $productId){
        $productId->deleted = '1';
        $productId->save();
        $productId->slider()->delete();
        Swal::success('حذف موفقیت آمیز' , 'محصول مورد نظر حذف شد.');
        return redirect()->back();
    }

    public function setAsPendingInAdminPanel(Request $request , Products $product){
        $product->status = 'pending';
        $product->save();
        Swal::success('در انتظار تایید قرار گرفتن محصول' , 'محصول مورد نظر در انتظار تایید قرار گرفت.');
        return redirect()->back();
    }

    public function setAsDeletedInAdminPanel(Request $request , $productId){
        $product = Products::find($productId);
        $product->status = 'deleted';
        $product->save();

        return redirect()->back()->with('success_msg' , 'محصول مورد نظر به لیست حذف شده ها منتقل شد.');
    }

    public function showAllProductsInAdmin(Request $request){
        $productObj = new Products();
        $allProducts= $productObj->select(Products::FIELDS)
            ->where('product.status' , '!=' , 'deleted')
            ->orderBy('product.updated_at' , 'desc')
            ->paginate(15);
        $data['all_products'] = $allProducts;
        $searchInProductsObj = new Products();
        $searchInProducts = $searchInProductsObj->select(Products::FIELDS);
        if($request->has('keyword')){
            $searchInProducts = $searchInProducts
                ->whereRaw('(MATCH( product.name , product.description ) AGAINST( "'. $request->keyword .'" IN NATURAL LANGUAGE MODE ))');
        }
        if($request->has('price') && $request->price != 'all'){
            switch ($request->price){
                case '-20000':
                    $searchInProducts = $searchInProducts->where('product.price' , '<' , 20000 );
                    break;
                case '20000-100000':
                    $searchInProducts = $searchInProducts->where('product.price' , '>=' , 20000)
                        ->where('product.price' , '<' , 100000);
                    break;
                case '100000-250000':
                    $searchInProducts = $searchInProducts->where('product.price' , '>=' , 100000)
                        ->where('product.price' , '<' , 250000);
                    break;
                case '250000-500000':
                    $searchInProducts = $searchInProducts->where('product.price' , '>=' , 250000)
                        ->where('product.price' , '<' , 500000);
                    break;
                case '+500000':
                    $searchInProducts = $searchInProducts->where('product.price' , '>=' , 500000 );
                    break;
            }
        }
        if($request->has('status') &&  in_array($request->status , ['approved' , 'pending' , 'rejected'])){
            $searchInProducts = $searchInProducts->where('product.status' , '=' , $request->status);
        }
        if($request->has('availability') && $request->availability != '-1'){
            $availability = $request->availability;
            if($availability == '1'){
                $searchInProducts = $searchInProducts->where('product.quantity' , '>' , 0);
            }else{
                $searchInProducts = $searchInProducts->where('product.quantity' , '=' , 0);
            }
        }
        if($request->has('visibility') && $request->visibility != '-1'){
            $visibility = $request->visibility;
            if($visibility == '1'){
                $searchInProducts = $searchInProducts->where('product.visible' , '=' , 1);
            }else{
                $searchInProducts = $searchInProducts->where('product.visible' , '=' , 0);
            }
        }
        $data['search_in_products'] = $searchInProducts->paginate(15);
        return view('backend.all_retail_products')->with($data);
    }

    public function changeStatus(Request $request , Products $products){
        $products->status = $request->status;
        $products->save();
        return redirect()->back()->with('success_msg' , 'وضعیت محصول با موفقیت تغییر کرد.');
    }

    public function showInAdminPanel(Request $request , Products $product){
        if($product->status == 'deleted'){
            abort(404);
        }
        $data['product'] = $product->select(Products::FIELDS)->where('product.id' , '=' , $product->id)->first();
        $data['photos'] = $product->photo;
        $data['totalSales'] = DB::table('bill_item')
            ->join('bill' , 'bill.id' , '=' , 'bill_item.bill_id')
            ->where('bill_item.product_id' , '=' , $product->id)
            ->sum(DB::raw('ROUND( bill_item.quantity * ( bill_item.price - ( ( bill_item.discount / 100 ) * bill_item.price ) ) )'));

        return view('backend.retailer_product')->with($data);
    }

    public function setProductVisible(Request $request , Products $product){
        $product->visible = 1;
        $product->save();

        Swal::success('نمایان سازی محصول' , 'محصول مورد نظر نمایان شد.');
        return redirect()->back();
    }

    public function setProductHidden(Request $request , Products $product){
        $product->visible = 0;
        $product->save();

        Swal::success('پنهان سازی محصول' , 'محصول مورد نظر پنهان شد.');
        return redirect()->back();
    }

    public function showCreatePage(Request $request)
    {
        $categories = Category::where('deleted' , '0')->get();
        return view('admin.product.adding-products' , compact('categories'));
    }

    public function save(saveNewProductRequest $request)
    {
        $product = new Products();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if($request->filled('discount')){
            $product->discount = $request->discount;
        }else{
            $product->discount = 0;
        }
        $product->quantity = $request->quantity;
        $product->visible = $request->visible;
        if ($request->has('is_vip')) {
            $product->is_vip = 1;
        } else {
            $product->is_vip = 0;
        }
        $product->subcatid = $request->subCategory;
        $product->slug = SlugService::createSlug(Products::class, 'slug', $product->name);
        if ($request->has('visible')) {
            $product->visible = 1;
        } else {
            $product->visible = 0;
        }
        $product->save();

        Swal::success('ثبت موفقیت آمیز محصول' , 'محصول شما با موفقیت ثبت شد.');
        return redirect()->route('showProductsPhotoCreatePage' , $product->id);
    }

    public function show(Request $request)
    {
        $this->validate($request , [
            'keyword' => 'nullable|string',
            'from' => 'nullable|numeric',
            'to' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
        ], [
            'keyword.string' => 'کلمه کلیدی نامعتبر است.',
            'from.numeric' => 'قیمت را بصورت عددی وارد نمایید.',
            'to.numeric' => 'قیمت را بصورت عددی وارد نمایید.',
            'discount.numeric' => 'تخفیف را بصورت عددی وارد نمایید.',
        ]);

        $productQ= new Products();
        $productQuery = $productQ->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.is_vip' , '=' , 0)
            ->orderBy('created_at' , 'desc');
        if($request->has('discount') && !empty($request->discount)){
            $productQuery = $productQuery->where('product.discount' , '=' , $request->discount);
        }
        if($request->has('keyword') && !empty($request->keyword)){
            $productQuery = $productQuery->where('product.name' , 'like' , "%". $request->keyword ."%");
        }
        if($request->has('from') && $request->has('to') && !empty($request->from) && !empty($request->to)){
            $productQuery = $productQuery->where('product.price' , '>=' , $request->from)
            ->where('product.price' , '<=' , $request->to);
        }
        if($request->has('subCategory') && $request->subCategory != 'all'){
            $productQuery = $productQuery->where('product.subcatid' , '=' , $request->subCategory);
        }else{
            if($request->has('category') && $request->category != 'all'){
                $productQuery = $productQuery->where('category.id' , '=' , $request->category);
            }
        }
        if($request->has('visible')){
            $productQuery = $productQuery->where('product.visible' , '=' , 1);
        }


        $productsList = $productQuery->paginate(15);
        foreach($productsList as $index => $row){
            $product = Products::find($row->id);
            if($product->slider()->exists()){
                $productsList[$index]->show_msg_for_deleting_product = true;
            }else{
                $productsList[$index]->show_msg_for_deleting_product = false;
            }
        }
        $data['products'] = $productsList;

        $categories = Category::where('deleted' , 0)->get();
        $data['categories'] = $categories;
        return view('admin.product.list-of-products')->with($data);
    }

    public function showVip(Request $request)
    {
        $this->validate($request , [
            'keyword' => 'nullable|string',
            'price' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
        ], [
            'keyword.string' => 'کلمه کلیدی نامعتبر است.',
            'price.numeric' => 'قیمت را بصورت عددی وارد نمایید.',
            'discount.numeric' => 'تخفیف را بصورت عددی وارد نمایید.',
        ]);

        $productQuery = new Products();
        $productQuery = $productQuery->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.is_vip' , '=' , 1)
            ->orderBy('created_at' , 'desc');

        if($request->has('discount')){
            $productQuery = $productQuery->where('product.discount' , '=' , $request->discount);
        }
        if($request->has('keyword')){
            $productQuery = $productQuery->where('product.name' , 'like' , "%". $request->keyword ."%");
        }
        if($request->has('subCategory') && $request->subCategory != 'all'){
            $productQuery = $productQuery->where('product.subcatid' , '=' , $request->subCategory);
        }else{
            if($request->has('category') && $request->category != 'all'){
                $productQuery = $productQuery->where('category.id' , '=' , $request->category);
            }
        }
        if($request->has('visible')){
            $productQuery = $productQuery->where('product.visible' , '=' , 1);
        }
        $productsList = $productQuery->paginate(15);
        foreach ($productsList as $index => $row) {
            $product = Products::find($row->id);
            if ($product->slider()->exists()){
                $productsList[$index]->show_msg_for_deleting_product = true;
            }else{
                $productsList[$index]->show_msg_for_deleting_product = false;
            }
        }
        $data['products'] = $productsList;

        $categories = Category::all();
        $data['categories'] = $categories;
        return view('admin.product.list-of-products')->with($data);
    }

    public function editProduct(Products $productId){
        $data['product'] = $productId;
        $subCategories = $productId->subCategory->category->subCategories;
        $data['subcategories'] = $subCategories;
        $categories = Category::where('deleted' , '0')->get();
        $data['categories'] = $categories;
        $productQuery = new Products();
        $productQuery = $productQuery->dbSelect(Products::FIELDS)->where('product.id' , '=' , $productId->id)->first();
        $data['productQuery'] = $productQuery;
        return view('admin.product.edit-page-products')->with($data);
    }

    public function updateProduct(Request $request , Products $productId){
//        dd($request->all());
        $productId->name = $request->name;
        $productId->description = $request->description;
        $productId->price = $request->price;
        if($request->has('discount')){
            $productId->discount = $request->discount;
        }else{
            $productId->discount = 0;
        }
        $productId->quantity = $request->quantity;
        $productId->visible = $request->visible;
        if ($request->has('is_vip')) {
            $productId->is_vip = 1;
        } else {
            $productId->is_vip = 0;
        }
        $productId->subcatid = $request->subCategory;
        if ($request->has('visible')) {
            $productId->visible = 1;
        } else {
            $productId->visible = 0;
        }
        $productId->save();


        Swal::success('ویرایش موفقیت آمیز' , 'محصول شما با موفقیت ویرایش شد.');
        return redirect()->route('showProductsPhotoEditPage' , $productId->id);
    }

    public function searchNames(Request $request){
        $name = $request->name;
        $products = Products::select('id' , 'name')->where('name' , 'like' , "%". $name ."%")
            ->where('product.deleted' , '=' , 0)
            ->get();
        return response()->json($products);
    }


    //////////////////////////////////////////frontend////////////////////////////////////////////////////
    public function showHomPage(Request $request)
    {
        $sliders = new Slider();
        $slider = $sliders->dbSelect(Slider::FIELDS)->get();
//        dd($slider);
        $data['sliders'] = $slider;

        $mostSellProductQuery =new Products();
        $mostSellProduct = $mostSellProductQuery->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
            ->where('product.quantity' , '>' , 0)
            ->orderBy('sale_price', 'desc')
            ->take(15)
            ->get();

        foreach ($mostSellProduct as $index => $row){

            if($row->first_photo){
                $mostSellProduct[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $row->first_photo;
            }else{
                $mostSellProduct[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
        }
        $data['mostSells'] = $mostSellProduct;

        $mostVisitProductQuery = new Products();
        $mostVisitedProduct = $mostVisitProductQuery->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
            ->where('product.quantity' , '>' , 0)
            ->orderBy('product.hits', 'desc')
            ->take(15)
            ->get();
//        dd($mostVisitedProduct);
        foreach ($mostVisitedProduct as $index => $row){
            if($row->first_photo){
                $mostVisitedProduct[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $row->first_photo;
            }else{
                $mostVisitedProduct[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
        }
        $data['mostVisited'] = $mostVisitedProduct;

        $vipProductQuery = new Products();
        $vipProduct = $vipProductQuery->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
            ->where('product.is_vip' , '=' , 1)
            ->where('product.quantity' , '>' , 0)
            ->orderBy('product.created_at', 'desc')
            ->take(15)
            ->get();
        foreach ($vipProduct as $index => $row){
            /*$photos = ProductPhoto::where('product_id' , $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }*/
            if($row->first_photo){
                $vipProduct[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $row->first_photo;
            }else{
                $vipProduct[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
//            $vipProduct[$index]->photos = $photos;
        }
        $data['vips'] = $vipProduct;

        $category = Category::all();
        /*foreach($category as $index => $row){
            $category[$index]->sub_categories = $row->subCategories;
        }*/
        $data['categories'] = $category;


//        dd($data);

        return view('frontend.home.index')->with($data);
    }

    public function search(Request $request)
    {
        $this->validate($request , [
            'search' => 'nullable|string',
            'category' => 'nullable|string|exists:category,id',
            'subCategory' => 'nullable|string|exists:sub_category,id',

        ], [
            'keyword.string' => 'کلمه کلیدی نامعتبر است.',
            'from.numeric' => 'قیمت را بصورت عددی وارد نمایید.',
            'to.numeric' => 'قیمت را بصورت عددی وارد نمایید.',
            'discount.numeric' => 'تخفیف را بصورت عددی وارد نمایید.',
        ]);

        $product = new Products();
        $product = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.quantity' , '>' , 0)
            ->where('product.visible' , '=' , 1);

        if($request->has('search')){
            $product = $product->where('product.name' , 'like' , "%{$request->search}%");
//            $product = $product->whereRaw("MATCH(product.name) AGAINST( '". $request->search."'IN NATURAL LANGUAGE MODE)");
        }
        if ($request->has('subCategory') && $request->subCategory != 'all'){
            $data['current_subcatgory'] = SubCategory::find($request->subCategory);
            $data['current_category'] = $data['current_subcatgory']->category;
            $product = $product->where('product.subcatid' , '=' , $request->subCategory);
        }else if($request->has('category')  && $request->category != 'all'){
            $product = $product->where('category.id' , '=' , $request->category);
            $data['current_category'] = Category::find($request->category);
            $data['current_subcatgory'] = null;
        }
        if ($request->has('list')) {
            if ($request->list == "vip") {
                if ($request->has('orderBy')){
                    if ($request->orderBy == 'priceD') {
                        $product = $product->orderBy('product.price', 'asc');
                    } elseif ($request->orderBy == 'priceA') {
                        $product = $product->orderBy('product.price', 'desc');
                    } elseif ($request->orderBy == 'newest') {
                        $product = $product->orderBy('product.created_at', 'desc');
                    } elseif ($request->orderBy == 'most-visit') {
                        $product = $product->orderBy('product.hits', 'desc');
                    }elseif ($request->orderBy == 'vip') {
                        $product = $product->where('product.is_vip', '=', 1);
                    }else {
                        $product = $product->orderBy('sale_price', 'desc');
                    }
                }else{
                    $product = $product->where('product.is_vip', '=', 1);
                }
            }
            if ($request->list == "newest") {
                if ($request->has('orderBy')){
                    if ($request->orderBy == 'priceD') {
                        $product = $product->orderBy('product.price', 'asc');
                    } elseif ($request->orderBy == 'priceA') {
                        $product = $product->orderBy('product.price', 'desc');
                    } elseif ($request->orderBy == 'newest') {
                        $product = $product->orderBy('product.created_at', 'desc');
                    } elseif ($request->orderBy == 'most-visit') {
                        $product = $product->orderBy('product.hits', 'desc');
                    } elseif ($request->orderBy == 'vip') {
                        $product = $product->where('product.is_vip', '=', 1);
                    } else {
                        $product = $product->orderBy('sale_price', 'desc');
                    }
                }else{
//                    $product = $product->where('product.is_vip', '=', 0)->orderBy('product.created_at', 'desc');
                    $product = $product->orderBy('product.created_at', 'desc');
                }
            }
            if ($request->list == "most-visit") {
                if ($request->has('orderBy')){
                    if ($request->orderBy == 'priceD') {
                        $product = $product->orderBy('product.price', 'asc');
                    } elseif ($request->orderBy == 'priceA') {
                        $product = $product->orderBy('product.price', 'desc');
                    } elseif ($request->orderBy == 'newest') {
                        $product = $product->orderBy('product.created_at', 'desc');
                    } elseif ($request->orderBy == 'most-visit') {
                        $product = $product->orderBy('product.hits', 'desc');
                    } elseif ($request->orderBy == 'vip') {
                        $product = $product->where('product.is_vip', '=', 1);
                    } else {
                        $product = $product->orderBy('sale_price', 'desc');
                    }
                }else{
//                    $product = $product->where('product.is_vip', '=' , 0)->orderBy('product.hits', 'desc');
                    $product = $product->orderBy('product.hits', 'desc');
                }
            }
            if ($request->list == "most-sell") {
                if ($request->has('orderBy')){
                    if ($request->orderBy == 'priceD') {
                        $product = $product->orderBy('product.price', 'asc');
                    } elseif ($request->orderBy == 'priceA') {
                        $product = $product->orderBy('product.price', 'desc');
                    } elseif ($request->orderBy == 'newest') {
                        $product = $product->orderBy('product.created_at', 'desc');
                    } elseif ($request->orderBy == 'most-visit') {
                        $product = $product->orderBy('product.hits', 'desc');
                    } elseif ($request->orderBy == 'vip') {
                        $product = $product->where('product.is_vip', '=', 1);
                    } else {
                        $product = $product->orderBy('sale_price', 'desc');
                    }
                }else{
//                    $product = $product->where('product.is_vip', '=', 0)->orderBy('sale_price', 'desc');
                    $product = $product->orderBy('sale_price', 'desc');
                }
            }
        }
        if ($request->has('subCategory') && $request->subCategory != ''){
            if ($request->orderBy == 'most-visit'){
                $product = $product
                    ->where('product.subcatid' , '=' , $request->subCategory)
                    ->orderBy('product.hits' , 'desc');
            }
            if ($request->orderBy == 'most-sell'){
                $product = $product
                    ->where('product.subcatid' , '=' , $request->subCategory)
                    ->orderBy('sale_price', 'desc');
            }
            if ($request->orderBy == 'vip'){
                $product = $product
                    ->where('product.is_vip', '=', 1)
                    ->where('product.subcatid' , '=' , $request->subCategory);
            }
            if ($request->orderBy == 'priceD'){
                $product = $product
                    ->where('product.subcatid' , '=' , $request->subCategory)
                    ->orderBy('product.price', 'asc');
            }
            if ($request->orderBy == 'priceA'){
                $product = $product
                    ->where('product.subcatid' , '=' , $request->subCategory)
                    ->orderBy('product.price', 'desc');
            }
            if ($request->orderBy == 'newest'){
                $product = $product
                    ->where('product.subcatid' , '=' , $request->subCategory)
                    ->orderBy('product.created_at', 'desc');
            }
        }/*subcategory*/

        if ($request->has('category') && $request->category != ''){
                if ($request->orderBy == 'most-visit'){
                    $product = $product
                        ->where('category.id' , '=' , $request->category)
                        ->orderBy('product.hits' , 'desc');
                }
                if ($request->orderBy == 'most-sell'){
                    $product = $product
                        ->where('category.id' , '=' , $request->category)
                        ->orderBy('sale_price', 'desc');
                }
                if ($request->orderBy == 'vip'){
                    $product = $product
                        ->where('product.is_vip', '=', 1)
                        ->where('category.id' , '=' , $request->category);
                }
                if ($request->orderBy == 'priceD'){
                    $product = $product
                        ->where('category.id' , '=' , $request->category)
                        ->orderBy('product.price', 'asc');
                }
                if ($request->orderBy == 'priceA'){
                    $product = $product
                        ->where('category.id' , '=' , $request->category)
                        ->orderBy('product.price', 'desc');
                }
                if ($request->orderBy == 'newest'){
                    $product = $product
                        ->where('category.id' , '=' , $request->category)
                        ->orderBy('product.created_at', 'desc');
                }
        }/*category*/

        $productList = $product->paginate(12);

        foreach($productList as $index => $row){
            if($row->first_photo){
                $productList[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $row->first_photo;
            }else{
                $productList[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
        }

        $data['products'] = $productList;
        $countProduct = $productList->total();
        $data['count'] = $countProduct;

        $categories = Category::all();
        $data['categories'] = $categories;

        $category = Category::where('id' , $request->category)->first();
        $data['cat'] = $category;
        $slider = new Slider();
        $sliderQuery = $slider->dbSelect(Slider::FIELDS)
            ->where('category.id' , '=' , $request->category)
            ->inRandomOrder()
            ->first();
        $data['slider'] = $sliderQuery;

        $sliderN = DB::table('slider')
            ->inRandomOrder()
            ->first();
        $data['sliderN'] = $sliderN;

        return view('frontend.list.index')->with($data);
    }

    public function showSinglePage(Request $request , Products $product)
    {
        $newProducts =new Products();
        $newestproducts = $newProducts->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
            ->orderBy('product.created_at', 'desc')
            ->where('product.quantity' , '>' , 0)
            ->take(15)
            ->get();
        foreach ($newestproducts as $index => $row){

            if($row->first_photo){
                $newestproducts[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $row->first_photo;
            }else{
                $newestproducts[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
//            $newestproducts[$index]->photos = $photos;
        }
//        dd($newestproducts);
        $data['newests'] = $newestproducts;
//        dd($product);
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.id' , '=' , $product->id)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
//            ->where('product.quantity' , '>' , 0)
            ->first();
        if(!$productQuery){
            abort(404);
        }
        $photos = ProductPhoto::where('product_id' , $productQuery->id)->get();
        foreach ($photos as $index => $photoItem){
            $photos[$index]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
        }
        $productQuery->photos = $photos;
//        dd($productQuery);

        $comment = new Comment();
        $comments = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.product_id' , $productQuery->id)
            ->where('comment.status' , 'approved')
            ->where('comment.parent_comment_id' , null)
            ->paginate(10);
        foreach ($comments as $cIndex => $commentItem){
            $comments[$cIndex]->comment = $commentItem->comment;
        }
        $productQuery->comments = $comments;

        $data['product'] = $productQuery;
        $allProduct = new Products();
        $similarProduct = $allProduct->dbSelect(Products::FIELDS)
            ->where('product.deleted' , '=' , 0)
            ->where('product.visible' , '=' , 1)
            ->where('product.quantity' , '>' , 0)
            ->where('category.id' , '=' , $productQuery->category_id)
            ->get();
        foreach($similarProduct as $pIndex => $pRow){
            if($pRow->first_photo){
                $similarProduct[$pIndex]->first_photo = \url()->to('/image/product_photos') . '/' . $pRow->first_photo;
            }else{
                $similarProduct[$pIndex]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
        }
//        dd($similarProduct);
        $data['similarProducts'] = $similarProduct;
        return view('frontend.product.show')->with($data);
    }

    public function autocomplete(Request $request)
    {
        $term = $request->term;
        $autocompleteProduct = new Products();
        $data = $autocompleteProduct->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->where('product.visible', '=', 1)
            ->where('product.quantity', '>', 0)
            ->where('product.name', 'LIKE', '%' . $term . '%')
            ->take(10)
            ->get();
        foreach ($data as $index => $pro) {
//            $data[$index]->product_url = route('singlePage' , $pro->slug);
            $result[] = array('id' => $pro->id, 'name' => $pro->name , 'value' =>  route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $pro->name, ['unique' => true])));
//            $result[] = ['id' => $pro->id , 'name' => $pro->name ];
        }

        return response()->json($result);
    }

    public function addToFav(Products $product)
    {
        $product->favoriteByUser()->sync([
            'user_id' => auth()->guard('web')->user()->id
        ]);
        Swal::success('افزودن به علاقمندی ها', 'محصول مورد نظر به لیست علاقمندی ها افزوده شد.');
        return redirect()->back();
    }

    public function removeToFav(Products $product)
    {
        $product->favoriteByUser()->detach([
            'user_id' => auth()->guard('web')->user()->id
        ]);
        Swal::success('حذف از علاقمندی ها', 'محصول مورد نظر از لیست علاقه مندی ها شد.');
        return redirect()->back();
    }
    public function getUserProductViaAjax(Request $request){
        $user = User::find($request->user_id);
        if(count($user->stores) == 0 ){
            return response()->json([
                'list' => []
            ] , 200);
        }
        $products = ProductSeller::join('store' , 'store.id' , '=' , 'product_seller.store_id')
            ->join('users' , 'users.id' , '=' , 'store.user_id')
            ->where('users.id' , $request->user_id)
            ->where('product_seller.status' , '!=' , 'deleted')
            ->select('product_seller.*')
            ->get();
        return response()->json([
            'list' => $products
        ] , 200);
    }
}

