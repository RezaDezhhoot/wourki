<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Bill;
use App\BillItem;
use App\Category;
use App\Charts\StoreChart;
use App\Guild;
use App\Helpers\DiscountQueryHelper;
use App\Helpers\ProductsQueryHelper;
use App\Helpers\RawQueries;
use App\Http\Requests\web\StoreProduct;
use App\Http\Requests\web\StoreService;
use App\Http\Requests\web\uploadPhotoRequest;
use App\Libraries\Swal;
use App\PlanSubscription;
use App\Product_seller_attribute;
use App\Product_seller_photo;
use App\ProductRate;
use App\ProductSeller;
use App\ProductSellerFavorite;
use App\Slider;
use App\Store;
use App\Store_photo;
use App\UpgradePosition;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Morilog\Jalali\Jalalian;

class ProductSellerController extends Controller
{
    public function index(Store $slug)
    {
        $storeInfo = Store::join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->where('store.slug', $slug->slug)
            ->select('store.name as store_name', 'users.first_name', 'users.thumbnail_photo', 'users.last_name', 'guild.name as guild_name', 'store.phone_number', 'users.mobile as user_mobile',
                'store.created_at', 'store.status as store_status', 'store.visible as store_visible', 'store.id as store_id', 'store.user_name', 'store.pay_type',
                'store.activity_type', 'store.slogan', 'store.shaba_code', 'users.id as user_id')
            ->first();
        $planSubs = PlanSubscription::where('user_id', $storeInfo->user_id)
            ->where('from_date', '<=', Carbon::now()->toDateString())
            ->where('to_date', '>=', Carbon::now()->toDateString())
            ->where('plan_type' , 'store')
            ->get();
        $minDate = '';
        $maxDate = '';
        foreach ($planSubs as $sub) {
            if ($minDate == '') {
                $minDate = $sub->from_date;
            } else {
                if (Carbon::createFromFormat('Y-m-d', $sub->from_date)->lt(Carbon::createFromFormat('Y-m-d', $minDate))) {
                    $minDate = $sub->min_date;
                }
            }
            if ($maxDate == '') {
                $maxDate = $sub->to_date;
            } else {
                if (Carbon::createFromFormat('Y-m-d', $sub->to_date)->gt(Carbon::createFromFormat('Y-m-d', $maxDate))) {
                    $maxDate = $sub->to_date;
                }
            }
        }
        if (count($planSubs) > 0) {
            $minDateCarbon = Carbon::createFromFormat('Y-m-d', $minDate);
            $maxDateCarbon = Carbon::createFromFormat('Y-m-d', $maxDate);
            $intervalDays = $maxDateCarbon->diffInDays($minDateCarbon);
        } else {
            $intervalDays = 0;
        }

//        $planSubs = PlanSubscription::where('user_id', $storeInfo->user_id)->latest()->first();
//        if ($planSubs) {
//            $planSubsFromDate = $planSubs->from_date;
//            $planSubsToDate = $planSubs->to_date;
//            $now = Carbon::now()->toDateString();
//            $minDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsFromDate);
//            $maxDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsToDate);
//            if ($minDateCarbon->toDateString() <= $now) {
//                $intervalDays = $maxDateCarbon->diffInDays($minDateCarbon);
//            } else {
//                $intervalDays = 0;
//            }
//        }
        $storePhotos = DB::table('store_photo')
            ->where('store_id', $storeInfo->store_id)
            ->get();


//        $bills = Bill::where('store_id', $slug->id)->get();
//        $billItem = new BillItem();
//        foreach ($bills as $index => $row) {
//            $bills[$index]->billItemPrice = $billItem->getBillItemPrice($row->id);
//            $bills[$index]->BillDate = Jalalian::forge($row->created_at)->format('Y/m/d');
//        }
//        $billsPrice = $bills->pluck('billItemPrice')->toArray();
//        $billsDate = $bills->pluck('BillDate')->toArray();
        $billsDate = Bill::where('bill.store_id', $slug->id)
            ->whereIn('bill.status', ['delivered', 'approved'])
            ->selectRaw('DATE(bill.created_at) as date')
            ->distinct()
            ->pluck('date');
        $billsPrices = [];
        $billsDateFa = [];
        foreach ($billsDate as $date) {
            $faDate = Jalalian::forge($date)->format('Y/m/d');
            $price = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
                ->whereDate('bill.created_at', '=', $date)
                ->whereIn('bill.status', ['delivered', 'approved'])
                ->sum(DB::raw('( bill_item.quantity * (bill_item.price - ( bill_item.discount / 100 * bill_item.price )) )'));
            $billsDateFa[] = $faDate;
            $billsPrices[] = $price;
        }
        $billStore = new StoreChart();
        $billStore
            ->labels($billsDateFa)
            ->dataset(
                'میزان فروش محصولات فروشگاه',
                'line',
                $billsPrices
            )
            ->options([
                'borderColor' => '#ff0000',
                'legend' => [
                    'display' => true
                ],
            ]);


        $products_seller = ProductSeller::where('store_id', $storeInfo->store_id)->latest()->paginate(20);
        $productSellerQuery = ProductSeller::where('store_id', $storeInfo->store_id)
            ->join('category', 'category.id', '=', 'product_seller.category_id')
            ->join('store', 'store.id', '=', 'product_seller.store_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->orderBy('product_seller.created_at', 'desc')
            ->select('product_seller.id', 'product_seller.name', 'product_seller.description',
                'product_seller.price', 'product_seller.discount', 'product_seller.quantity', 'product_seller.visible', 'category.name as category_name', 'category.id as category_id',
                'store.name as store_name', 'store.id as store_id', 'store.user_name as store_user_name', 'product_seller.status', 'product_seller.is_vip', 'product_seller.hint',
                'guild.name as guild_name',
                DB::raw('(
                    select count(*) from product_seller_photo
                    where product_seller_photo.seller_product_id = product_seller.id
                ) as count_of_photos'),
                DB::raw('(
                    select count(*) 
                    from product_seller_comment
                    where product_seller_comment.product_seller_id = product_seller.id
                ) as comments_count'))
            ->where('product_seller.status', '!=', 'deleted')
            ->get();
        foreach ($productSellerQuery as $pIndex => $pSeller) {
            $totalBillsSales = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
                ->join('product_seller', 'product_seller.id', '=', 'bill_item.product_id')
                ->where('bill_item.product_id', '=', $pSeller->id)
                ->sum(DB::raw('(bill_item.quantity * (bill_item.price -  ( bill_item.discount / 100 * bill_item.price ) ) )'));

            $totalAttributeSales = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
                ->join('bill_item_attribute', 'bill_item_attribute.bill_item_id', '=', 'bill_item.id')
                ->join('product_seller', 'product_seller.id', '=', 'bill_item.product_id')
                ->where('product_seller.id', '=', $pSeller->id)
                ->sum('bill_item_attribute.extra_price');
            $productSellerQuery[$pIndex]->total_sales = $totalBillsSales + $totalAttributeSales;

            $productSellerAttributes = Product_seller_attribute::join('attribute', 'attribute.id', '=', 'product_seller_attribute.attribute_id')
                ->join('product_seller', 'product_seller.id', '=', 'product_seller_attribute.product_seller_id')
                ->where('product_seller_id', $pSeller->id)
                ->where('product_seller_attribute.deleted', 0)
                ->select('product_seller_attribute.title', 'product_seller_attribute.extra_price', 'product_seller_attribute.id', 'attribute.type', 'attribute.id as attr_id', 'product_seller.name')
                ->get();

            $productSellerQuery[$pIndex]->attributes = $productSellerAttributes;
        }
        $productSellerCategories = Category::all();
        $attributesName = Attribute::all();
        return view('admin.product_seller.index', compact('attributesName', 'products_seller', 'storeInfo', 'storePhotos', 'slug', 'intervalDays', 'billStore', 'productSellerQuery', 'productSellerCategories'));
    }

    public function makeApprovedStatus(ProductSeller $product)
    {
        $product->status = 'approved';
        $product->save();

        return response()->json('status', 200);
    }

    public function makeRejectStatus(ProductSeller $product)
    {
        $product->status = 'rejected';
        $product->save();

        return response()->json('status', 200);
    }

    public function makePendingStatus(ProductSeller $product)
    {
        $product->status = 'pending';
        $product->save();

        return response()->json('status', 200);
    }

    public function showProduct($store, $product)
    {
        $productInfo = ProductSeller::join('store', 'store.id', 'product_seller.store_id')
            ->join('category', 'category.id', '=', 'product_seller.category_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->where('product_seller.id', $product)
            ->select('store.name as store_name', 'product_seller.description', 'category.name as category_name', 'product_seller.name as product_seller_name',
                'guild.name as guild_name', 'product_seller.visible', 'product_seller.price', 'product_seller.discount', 'store.user_name', 'product_seller.id', 'product_seller.status',
                'product_seller.shipping_price_to_tehran' , 'product_seller.shipping_price_to_other_towns' , 'product_seller.deliver_time_in_tehran',
                'product_seller.deliver_time_in_other_towns')
            ->first();
        $productSeller = ProductSeller::find($product);
        $productPhotos = $productSeller->photos;
        $productComments = $productSeller->comments;
        return view('admin.product_seller.product', compact('productInfo', 'store', 'productPhotos', 'productComments'));
    }

    public function editProductPhotos($store, $product)
    {
        $productSeller = ProductSeller::find($product);
        $productPhotos = $productSeller->photos;
        $storess = Store::where('user_name', $store)->first();
        return view('admin.product_seller.edit_photo', compact('store', 'storess', 'product', 'productPhotos', 'productSeller'));
    }

    public function updateProductPhotos(uploadPhotoRequest $request, $store, $product)
    {
        if ($request->hasFile('photo')) {
            foreach ($request->photo as $photo) {

                $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();

                Image::make($photo)
                    ->fit(1920, 1080, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->save(public_path('/image/product_seller_photo/') . '/' . $imageName);
                Image::make($photo)
                    ->fit(350, 350, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->save(public_path('/image/product_seller_photo/350') . '/' . $imageName);

//                $photo->move(public_path('/image/product_seller_photo/'), $fileName);
                $product_seller_photo = new Product_seller_photo();
                $product_seller_photo->seller_product_id = $product;
                $product_seller_photo->file_name = $imageName;
                $product_seller_photo->save();
            }
        } else {
            Swal::error('آپلود فایل', 'عکسی جهت آپلود انتخاب نشده است.');
            return redirect()->back();
        }
        Swal::success('آپلود فایل', 'فایل ها با موفقیت آپلود شدند.');
        return redirect()->back();
    }

    public function deleteProductPhotos($store, Product_seller_photo $product)
    {
        $product->delete();
//        unlink(public_path('/image/product_seller_photo/') . $product->file_name);
        Swal::success('حذف موفقیت آمیز.', 'عکس مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function getByAjax(Request $request)
    {
        $products = ProductSeller::where('store_id', $request->id)
            ->where('status', 'approved')
            ->orderBy('name', 'asc')
            ->select('name', 'id')
            ->get();
        return response()->json($products, 200);
    }

    public function getProductsByNameViaAjax(Request $request){
        $this->validate($request, [
            'q' => 'required|string|max:255'
        ]);
        $products = ProductSeller::where('name' , "like","%" . $request->q . "%")
            ->where('status', 'approved')
            ->orderBy('name', 'asc')
            ->select('name', 'id')
            ->get();
        return response()->json($products, 200);
    }

    public function userProducts()
    {
        $categories = null;
        $userStore = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type' , 'product')
            ->first();
        if ($userStore) {
            $categories = Category::where('guild_id', $userStore->guild_id)->get();
        }
        $is_service = false;
        $features = Attribute::where('store_type' , 'product')->get();
        return view('frontend.my-account.products.create', compact('categories' , 'is_service' , 'userStore' , 'features'));
    }
    public function userServices()
    {
        $categories = null;
        $userStore = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type' , 'service')
            ->first();
        if ($userStore) {
            $categories = Category::where('guild_id', $userStore->guild_id)->get();
        }
        $is_service = true;
        $features = Attribute::where('store_type', 'service')->get();
        return view('frontend.my-account.products.create', compact('categories' , 'is_service' , 'userStore' , 'features'));
    }

    public function createUserProduct(StoreProduct $request)
    {
        $store = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type' , 'product')->first();
        $product = new ProductSeller();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->quantity = $request->quantity;
        if($request->has('guarantee_mark')){
        $product->guarantee_mark = 1;
        }else{
            $product->guarantee_mark = 0;
        }
        if($request->has('deliver_today_in_tehran')){
        $product->deliver_time_in_tehran = 0;
        }else{
            $product->deliver_time_in_tehran = $request->deliver_day_in_tehran;
        }
        if($request->has('deliver_today_in_other_towns_check')){
        $product->deliver_time_in_other_towns = 0;
        }else{
            $product->deliver_time_in_other_towns = $request->deliver_day_in_other_towns;
        }
        if($request->has('delivery_in_tehran_without_price')){
        $product->shipping_price_to_tehran = 0;
        }else{
            $product->shipping_price_to_tehran = $request->shipping_price_to_tehran;
        }
        if($request->has('free_shipping_to_other_towns')){
        $product->shipping_price_to_other_towns = 0;
        }else{
            $product->shipping_price_to_other_towns = $request->shipping_price_to_other_towns;
        }
        if ($request->filled('visible'))
            $product->visible = 1;
        else
            $product->visible = 0;
        if ($request->discount != null)
            $product->discount = $request->discount;
        else
            $product->discount = 0;
        $product->category_id = $request->category;
        $product->store_id = $store->id;
        $product->save();

        Swal::success('ثبت موفقیت آمیز محصول.', 'محصول مورد نظر با موفقیت در فروشگاه شما ثبت شد.');
        return redirect()->route('user.product.photo', $product->id);
    }
    public function createUserService(StoreService $request)
    {
        $store = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type' , 'service')->first();
        $service = new ProductSeller();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->discount = $request->discount;
        $service->quantity = $request->quantity;
        $service->guarantee_mark = 0;
        if ($request->has('deliver_today_in_tehran')) {
            $service->deliver_time_in_tehran = 0;
        } else {
            $service->deliver_time_in_tehran = $request->deliver_day_in_tehran;
        }
        if ($request->has('deliver_today_in_other_towns_check')) {
            $service->deliver_time_in_other_towns = 0;
        } else {
            $service->deliver_time_in_other_towns = $request->deliver_day_in_other_towns;
        }
        if ($request->has('delivery_in_tehran_without_price')) {
            $service->shipping_price_to_tehran = 0;
        } else {
            $service->shipping_price_to_tehran = $request->shipping_price_to_tehran;
        }
        if ($request->has('free_shipping_to_other_towns')) {
            $service->shipping_price_to_other_towns = 0;
        } else {
            $service->shipping_price_to_other_towns = $request->shipping_price_to_other_towns;
        }
        if ($request->filled('visible'))
        $service->visible = 1;
        else
            $service->visible = 0;
        if ($request->discount != null)
            $service->discount = $request->discount;
        else
            $service->discount = 0;
        $service->category_id = $request->category;
        $service->store_id = $store->id;
        $service->save();

        Swal::success('ثبت موفقیت آمیز محصول.', 'محصول مورد نظر با موفقیت در فروشگاه شما ثبت شد.');
        return redirect()->route('user.service.photo', $service->id);
    }

    public function products()
    {
        $visibleUserProducts = Collection::make([]);
        $hiddenUserProducts = Collection::make([]);
        $userStore = Store::where('user_id' , auth()->guard('web')->user()->id)
            ->where('store_type' , 'product')
            ->first();
        if ($userStore) {
            $visibleUserProducts = ProductSeller::where('store_id', $userStore->id)
                ->where('visible', 1)
                ->where('status', '!=', 'deleted')
                ->get();
            $hiddenUserProducts = ProductSeller::where('store_id', $userStore->id)
                ->where('visible', 0)
                ->where('status', '!=', 'deleted')
                ->get();
            foreach ($visibleUserProducts as $product) {
                $product->photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
            }
            foreach ($hiddenUserProducts as $product) {
                $product->photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
            }
        }
        $is_service = false;
        $positions = UpgradePosition::all();
        $allUserProducts = $hiddenUserProducts->merge($visibleUserProducts);
        return view('frontend.my-account.products.index', compact('allUserProducts','visibleUserProducts', 'hiddenUserProducts', 'is_service' , 'userStore' , 'positions'));
    }
    public function services()
    {
        $visibleUserProducts = Collection::make([]);
        $hiddenUserProducts = Collection::make([]);
        $userStore = Store::where('user_id' , auth()->guard('web')->user()->id)
            ->where('store_type' , 'service')
            ->first();
        if ($userStore) {
            $visibleUserProducts = ProductSeller::where('store_id', $userStore->id)
                ->where('visible', 1)
                ->where('status', '!=', 'deleted')
                ->get();
            $hiddenUserProducts = ProductSeller::where('store_id', $userStore->id)
                ->where('visible', 0)
                ->where('status', '!=', 'deleted')
                ->get();
            foreach ($visibleUserProducts as $product) {
                $product->photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
            }
            foreach ($hiddenUserProducts as $index => $product) {
                $product->photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
            }
        }
        $is_service = true;
        $positions = UpgradePosition::all();
        $allUserProducts = $hiddenUserProducts->merge($visibleUserProducts);
        return view('frontend.my-account.products.index', compact('allUserProducts','visibleUserProducts', 'hiddenUserProducts' , 'is_service' , 'userStore' , 'positions'));
    }
    public function edit(ProductSeller $product)
    {
        $userStore = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type' , 'product')->first();
        $categories = Category::where('guild_id', $userStore->guild_id)->get();
        $is_service = false;
        return view('frontend.my-account.products.edit', compact('categories', 'product' , 'is_service'));
    }
    public function editService(ProductSeller $product)
    {
        $userStore = Store::where('user_id', auth()->guard('web')->user()->id)->
            where('store_type' , 'service')->first();
        $categories = Category::where('guild_id', $userStore->guild_id)->get();
        $is_service = true;
        return view('frontend.my-account.products.edit', compact('categories', 'product' , 'is_service'));
    }


    public function update(ProductSeller $product, StoreProduct $request)
    {
        $userStore = auth()->guard('web')->user()->store;
        $inputs['name'] = $request->name;
        $inputs['quantity'] = $request->quantity;
        $inputs['description'] = $request->description;
        $inputs['discount'] = $request->discount;
        $inputs['price'] = $request->price;
        $inputs['category_id'] = $request->category;
        $inputs['store_id'] = $userStore->id;
        $inputs['status'] = 'pending';
        if ($request->filled('visible'))
            $inputs['visible'] = 1;
        else
            $inputs['visible'] = 0;

        $inputs['guarantee_mark'] = 1;
        $inputs['deliver_time_in_tehran'] = 0;
        $inputs['deliver_time_in_other_towns'] = 0;
        $inputs['shipping_price_to_tehran'] = 0;
        $inputs['shipping_price_to_other_towns'] = 0;

        if($request->has('guarantee_mark')){
            $inputs['guarantee_mark'] = 1;
        }else{
            $inputs['guarantee_mark'] = 0;
        }
        if($request->has('deliver_today_in_tehran')){
            $inputs['deliver_time_in_tehran'] = 0;
        }else{
            $inputs['deliver_time_in_tehran'] = $request->deliver_day_in_tehran;
        }
        if($request->has('deliver_today_in_other_towns_check')){
            $inputs['deliver_time_in_other_towns'] = 0;
        }else{
            $inputs['deliver_time_in_other_towns'] = $request->deliver_day_in_other_towns;
        }
        if($request->has('delivery_in_tehran_without_price')){
            $inputs['shipping_price_to_tehran'] = 0;
        }else{
            $inputs['shipping_price_to_tehran'] = $request->shipping_price_to_tehran;
        }
        if($request->has('free_shipping_to_other_towns')){
            $inputs['shipping_price_to_other_towns'] = 0;
        }else{
            $inputs['shipping_price_to_other_towns'] = $request->shipping_price_to_other_towns;
        }

        $product->update($inputs);
        Swal::success('ویرایش موفقیت.', 'محصول مورد نظر با موفقیت ویرایش شد. پس از تایید مدیر در سایت نشان داده خواهد شد.');
        return redirect()->route('user.products');
    }

    public function updateService(ProductSeller $product, StoreService $request)
    {
        $userStore = auth()->guard('web')->user()->store;
        $inputs['name'] = $request->name;
        $inputs['description'] = $request->description;
        $inputs['discount'] = $request->discount;
        $inputs['price'] = $request->price;
        $inputs['category_id'] = $request->category;
        $inputs['store_id'] = $userStore->id;
        $inputs['status'] = 'pending';
        if ($request->filled('visible'))
        $inputs['visible'] = 1;
        else
            $inputs['visible'] = 0;
        $inputs['deliver_time_in_tehran'] = 0;
        $inputs['deliver_time_in_other_towns'] = 0;
        $inputs['shipping_price_to_tehran'] = 0;
        $inputs['shipping_price_to_other_towns'] = 0;

        if ($request->has('deliver_today_in_tehran')) {
            $inputs['deliver_time_in_tehran'] = 0;
        } else {
            $inputs['deliver_time_in_tehran'] = $request->deliver_day_in_tehran;
        }
        if ($request->has('deliver_today_in_other_towns_check')) {
            $inputs['deliver_time_in_other_towns'] = 0;
        } else {
            $inputs['deliver_time_in_other_towns'] = $request->deliver_day_in_other_towns;
        }
        if ($request->has('delivery_in_tehran_without_price')) {
            $inputs['shipping_price_to_tehran'] = 0;
        } else {
            $inputs['shipping_price_to_tehran'] = $request->shipping_price_to_tehran;
        }
        if ($request->has('free_shipping_to_other_towns')) {
            $inputs['shipping_price_to_other_towns'] = 0;
        } else {
            $inputs['shipping_price_to_other_towns'] = $request->shipping_price_to_other_towns;
        }

        $product->update($inputs);
        Swal::success('ویرایش موفقیت.', 'خدمت مورد نظر با موفقیت ویرایش شد. پس از تایید مدیر در سایت نشان داده خواهد شد.');
        return redirect()->route('user.products');
    }

    public function userFavorite()
    {
        $products = ProductSellerFavorite::join('product_seller', 'product_seller.id', '=', 'product_seller_favorite.product_id')
            ->where('product_seller_favorite.user_id', auth()->guard('web')->user()->id)
            ->get();
        $products->each(function ($product) {
            $product->photo = optional(Product_seller_photo::where('seller_product_id', $product->id)->first())->file_name;
            $product->discountPrice = $product->price - (($product->price * $product->discount) / 100);
        });
        return view('frontend.my-account.favorite.index', compact('products'));
    }

    public function show(Request $request,ProductSeller $product)
    {
        $product->update(['hint' => $product->hint += 1]);
        $product->discountPrice = $product->price - (($product->price * $product->discount) / 100);
        $productFavoriteExists = false;
        if (auth()->guard('web')->check())
            $productFavoriteExists = ProductSellerFavorite::where('user_id', auth()->guard('web')->user()->id)
                ->where('product_id', $product->id)->exists();
        $photos = $product->photos;
        $comments = $product->comments()
            ->whereNull('product_seller_comment.parent_comment_id')
            ->with(['user' , 'responses'])
            ->where('status', 'approved')
            ->get();
        $attributes = $product->attributes()->select('attribute_id')->distinct()->pluck('attribute_id')->toArray();
        $productAttrs = Attribute::whereIn('id', $attributes)->with(['productSellerAttributes' => function ($query) use ($product) {
            $query->where('product_seller_id', $product->id)
                ->where('deleted', 0);
        }])->get();
        $rate = ProductRate::where('product_seller_id', $product->id)
            ->avg('rate');
        if (!$rate) {
            $rate = 0;
        }
        $store = $product->store;
        $queryHelper = new ProductsQueryHelper();
        $similarProducts = $queryHelper->similarProducts($product);
        $othersSeen = $queryHelper->othersSeen($product);
        $suggestions = $queryHelper->productSuggestions($product);
        if($request->has('code')){
            $store = Store::where('store_type' , 'market')->where('id' , $request->code)->first();
            if(!$store){
                return 'لینک معرفی شما معتبر نیست';
            }
            $p = $store->products()->where('product_seller.id' , $product->id)->latest();
            if(!$p){
                return 'لینک معرفی شما معتبر نیست';
            }
            // reaching here means link is valid so lets store info in session
            session()->put('validated_market_code' , $store->id);
            session()->put('validated_product_id' , $product->id);
        }
        return view('frontend.product2.show', compact('product', 'photos', 'comments', 'attributes', 'similarProducts', 'productAttrs', 'productFavoriteExists', 'rate' , 'store' , 'suggestions' , 'othersSeen'));
    }

    public function toggleFavorite(Request $request)
    {
        $user = User::find(auth()->guard('web')->user()->id);
        $user->favoriteProducts()->toggle($request->product);
        return response()->json([ "status" => 200], 200);
    }

    public function productsList(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|max:100',
            'search_in' => 'required|string|in:product,service,store,store_id',
            'category' => 'nullable|numeric|exists:category,id',
            'city' => 'nullable|numeric|exists:city,id',
        ]);
        if ($request->cookie('province')) {
            $province = Cookie::get('province');
        } else {
            $province = 4;
        }
        $sliders = Slider::where('type' , $request->search_in)->get();
        foreach ($sliders as $sIndex => $sRow) {
            if ($sRow->product_id !== null) {
                $sliders[$sIndex]->link = route('show.product.seller', $sRow->product_id);
            } else if ($sRow->store_id !== null) {
                $sliders[$sIndex]->link = route('show.store', $sRow->store->user_name);
            } else {
                $sliders[$sIndex]->link = null;
            }
        }
        if ($request->search_in == 'product' || $request->search_in == 'service') {
            $products = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('users', 'users.id', '=', 'store.user_id')
                ->join('category', 'category.id', '=', 'product_seller.category_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->leftJoin('upgrades', 'product_seller.id', '=', 'upgradable_id')
                ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
                ->where(function ($query) {
                    return $query->where('upgrades.upgradable_type', ProductSeller::class)->orWhereNull('upgrades.upgradable_type');
                })
                ->where(function  ($query) use ($request){
                    return $query->where('upgrade_positions.position', $request->search_in . '_in_page')->orWhereNull('upgrade_positions.position');
                })
                ->groupBy('product_seller.id')
                ->whereRaw(RawQueries::hasProductsForStore())
                ->whereRaw(RawQueries::hasSubscriptionForProduct())
                ->where('store.status', '=', 'approved')
                ->where('store.visible', '=', 1)
                ->where('product_seller.status', 'approved')
                ->where('product_seller.visible', 1)
                ->where(function ($query){
                    $query->where('product_seller.quantity' , '>' , '0')->orWhereRaw('product_seller.quantity IS NULL');
                })
                ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.description', 'product_seller.category_id', 'product_seller.hint'
                    , 'product_seller.discount', 'store.activity_type' , 'store.store_type' , DB::raw('MAX(upgrades.from_marketer) as marketer'))
                ->addSelect(DB::raw('(
                    select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
                ) as discountPrice'))
                ->orderBy('upgrades.updated_at', 'desc');

            if ($request->orderBy == 'vip') {
                $products->where('product_seller.is_vip', 1)
                    ->orderBy('product_seller.id', 'desc');
            }
            $products->where('store.store_type' , $request->search_in);
            if ($request->orderBy == 'high-sales') {
                $products->addSelect(DB::raw('(
                    select round(sum(((bill_item.price - ((bill_item.price * bill_item.discount) / 100))) * bill_item.quantity))  
                    from bill_item 
                    join bill ON bill.id = bill_item.bill_id 
                    where bill_item.product_id = product_seller.id and (bill.status = "delivered" or bill.status = "approved")
                ) as sale_price'))
                    ->orderBy('sale_price', 'desc');
            }
            if ($request->orderBy == 'high-visited') {
                $products->orderBy('product_seller.hint', 'desc');
            }
            if ($request->orderBy == 'cut-rate') {
                $products->where('product_seller.discount', '!=', 0);
            }
            if ($request->orderBy == 'newest') {
                $products->orderBy('product_seller.id', 'desc');
            }
            if ($request->orderBy == 'most-expensive') {
                $products->orderBy('product_seller.price', 'desc');
            }
            if ($request->orderBy == 'cheapest') {
                $products->orderBy('product_seller.price', 'asc');
            }
            if ($request->filled('keyword')) {
                $products->where('product_seller.name', 'like', '%' . $request->keyword . '%');
            }
            if ($request->has('category')) {
                $products->where('product_seller.category_id', $request->category);
            }
            if ($request->has('city')) {
                $products->where('city.id', $request->city);
            }
            else{
                $products->where(function ($activityTypeSubQuery) use ($province) {
                    $activityTypeSubQuery->where('store.activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($province) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $province);
                        });
                });
            }

            $products = $products->paginate(16)->appends([
                'search_in' => $request->search_in,
                'orderBy' => $request->orderBy,
                'category' => $request->category,
                'city' => $request->city,
                'keyword' => $request->keyword,
            ]);
            $products->each(function ($product) {
                $product->photo = optional($product->photos->first())->file_name;
            });
            $discountsQueryHelper = new DiscountQueryHelper();
            $discounts=[];
            if($request->search_in == 'product'){
                $discounts = $discountsQueryHelper->getProductDiscountsMadeByUsers();
            }
            if ($request->search_in == 'service') {
                $discounts = $discountsQueryHelper->getServiceDiscountsMadeByUsers();
            }
            return view('frontend.product2.list', compact('products' , 'sliders' , 'discounts'));
        }else if($request->search_in == 'store_id'){
            $guilds = Guild::all();
            $stores = Store::join('address', 'address.id', '=', 'store.address_id')
                ->join('users', 'users.id', '=', 'store.user_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->join('guild', 'guild.id', '=', 'store.guild_id')
                ->whereRaw(RawQueries::hasProductsForStore())
                ->whereRaw(RawQueries::hasSubscriptionForStore())
                ->where('store.status', '=', 'approved')
                ->where('store.visible', '=', 1)
                ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.user_name', 'store.activity_type')
                ->addSelect(DB::raw('(
                    select avg(store_rate.rate)
                    from store_rate
                    where store.id = store_rate.store_id
                ) as rate'));

            if ($request->filled('keyword')) {
                $stores->where('store.user_name', 'like', '%' . $request->keyword . '%');
            }

            $stores = $stores->paginate(15)->appends([
                'search_in' => $request->search_in,
                'keyword' => $request->keyword,
            ]);
            // $stores->each(function ($store) {
            //     $store->photo = optional($store->photos->first())->photo_name;
            // });

            return view('frontend.store.list', compact('stores', 'guilds' , 'sliders'));
        } else {
            $guilds = Guild::all();
            $stores = Store::join('address', 'address.id', '=', 'store.address_id')
                ->join('users', 'users.id', '=', 'store.user_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->join('guild', 'guild.id', '=', 'store.guild_id')
                ->leftJoin('upgrades', 'store.id', '=', 'upgradable_id')
                ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
                ->where(function ($query) {
                    return $query->where('upgrades.upgradable_type', Store::class)->orWhereNull('upgrades.upgradable_type');
                })
                ->where(function ($query) {
                    return $query->where('upgrade_positions.position', 'store_in_newest')->orWhereNull('upgrade_positions.position');
                })
                ->groupBy('store.id')
                ->whereRaw(RawQueries::hasProductsForStore())
                ->whereRaw(RawQueries::hasSubscriptionForStore())
                ->where('store.status', '=', 'approved')
                ->where('store.visible', '=', 1)
                ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.user_name', 'store.activity_type')
                ->addSelect(DB::raw('(
                    select avg(store_rate.rate)
                    from store_rate
                    where store.id = store_rate.store_id
                ) as rate'))
                ->orderBy('upgrades.updated_at', 'desc');

            if ($request->filled('keyword')) {
                $stores->where('store.name', 'like', '%' . $request->keyword . '%');
            }
            if ($request->has('category')) {
                $guild = Category::find($request->category)->guild;
                $stores->where('store.guild_id', $guild->id);
            }
            if ($request->has('city')) {
                $stores->where('city.id', $request->city);
            }
            else{
                $stores->where(function ($activityTypeSubQuery) use ($province) {
                    $activityTypeSubQuery->where('store.activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($province) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $province);
                        });
                });
            }
            if ($request->has('guild')) {
                $stores->where('store.guild_id', $request->guild);
            }

            $stores = $stores->paginate(15)->appends([
                'search_in' => $request->search_in,
                'orderBy' => $request->orderBy,
                'category' => $request->category,
                'city' => $request->city,
                'keyword' => $request->keyword,
            ]);
            $stores->each(function ($store) {
                $store->photo = optional($store->photo)->photo_name;
            });

            return view('frontend.store.list', compact('stores', 'guilds' , 'sliders'));
        }
    }

    public function makeStatusDelete(ProductSeller $product)
    {
        $product->update(['status' => 'deleted']);
        return back();
    }

    public function adminEditPage(ProductSeller $product)
    {
        $categories = Category::all();
        return view('admin.product_seller.edit_product', compact('product', 'categories'));
    }

    public function adminUpdate(ProductSeller $product, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:300',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|numeric|min:1',
            'category' => 'required|exists:category,id',
            'shipping_price_to_tehran' => 'required|numeric|min:0',
            'shipping_price_to_other_towns' => 'required|numeric|min:0',
            'deliver_time_in_tehran' => 'required|numeric|min:0',
            'deliver_time_in_other_towns' => 'required|numeric|min:0'
        ]);
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'discount' => $request->filled('discount') ? $request->discount : 0,
            'category_id' => $request->category,
            'description' => $request->description,
            'shipping_price_to_tehran' => $request->shipping_price_to_tehran,
            'shipping_price_to_other_towns' => $request->shipping_price_to_other_towns,
            'deliver_time_in_tehran' => $request->deliver_time_in_tehran,
            'deliver_time_in_other_towns' => $request->deliver_time_in_other_towns,
        ]);
        Swal::success('موفقیت آمیز.', 'ویرایش محصول با موفقیت انجام شد.');
        return back();
    }

    public function getProductsForAdsPageViaAjax(Request $request){
        $this->validate($request , [
            'q' => 'required|string|min:3',
        ]);
        $products = ProductSeller::where('name' , 'like' , "%". $request->q ."%")
            ->where('status' , '!=' , 'deleted')
            ->select('id' , 'name')
            ->get();
        return response()->json($products, 200);
    }

}
