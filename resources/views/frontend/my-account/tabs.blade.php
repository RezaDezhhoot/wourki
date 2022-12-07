<div class="tabs-container container-fluid">
    <div class="flex-wrapper my-account-tab-items">
        <ul class="list-inline">
            <li class="list-inline {{ Route::current()->getName() === 'user.profile' ? 'active' : ''  }}">
                <a href="{{ route('user.profile') }}">
                    <i class="fas fa-users-cog"></i>
                    پروفایل</a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.address' ? 'active' : '' }}">
                <a href="{{ route('user.address') }}">
                    <i class="fas fa-map-marker-alt"></i>
                    آدرس های من
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.plans' ? 'active' : '' }}">
                <a href="{{ route('user.plans') }}">
                    <i class="fas fa-certificate"></i>
                    خرید پلن
                </a>
            </li>
            @if(\App\Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'product')->count() > 0)
                <li class="list-inline {{ Route::current()->getName() === 'edit.store.page' ? 'active' : '' }}">
                    <a href="{{ route('edit.store.page') }}">
                        <i class="fas fa-store"></i>
                        ویرایش فروشگاه محصولات
                    </a>
                </li>
            @else
                <li class="list-inline {{ Route::current()->getName() === 'create.store.page' ? 'active' : '' }}">
                    <a href="{{ route('create.store.page') }}">
                        <i class="fas fa-store"></i>
                        ثبت فروشگاه محصولات
                    </a>
                </li>
            @endif
            @if(\App\Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'service')->count() > 0)
                <li class="list-inline {{ Route::current()->getName() === 'edit.service_store.page' ? 'active' : '' }}">
                    <a href="{{ route('edit.service_store.page') }}">
                        <i class="fas fa-tasks"></i>
                        ویرایش فروشگاه خدمات
                    </a>
                </li>
            @else
                <li class="list-inline {{ Route::current()->getName() === 'create.service_store.page' ? 'active' : '' }}">
                    <a href="{{ route('create.service_store.page') }}">
                        <i class="fas fa-tasks"></i>
                        ثبت فروشگاه خدمات
                    </a>
                </li>
            @endif
            @if(\App\Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'market')->count() > 0)
                <li class="list-inline {{ Route::current()->getName() === 'edit.market.page' ? 'active' : '' }}">
                    <a href="{{ route('edit.market.page') }}">
                        <i class="fas fa-box"></i>
                     فروشگاه بازاریابی
                    </a>
                </li>
                <li class="list-inline {{ Route::current()->getName() === 'marketers.page' ? 'active' : '' }}">
                    <a href="{{ route('marketers.page') }}">
                        <i class="fas fa-users"></i>
                     بازاریاب ها
                    </a>
                </li>
            @else
                <li class="list-inline {{ Route::current()->getName() === 'create.market.page' ? 'active' : '' }}">
                    <a href="{{ route('create.market.page') }}">
                        <i class="fas fa-box"></i>
                         فروشگاه بازاریابی
                    </a>
                </li>
            @endif
            <li class="list-inline {{ Route::current()->getName() === 'user.product.create.page' ? 'active' : '' }}">
                <a href="{{ route('user.product.create.page') }}">
                    <i class="fas fa-cart-plus"></i>
                    ثبت محصول
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.products' ? 'active' : '' }}">
                <a href="{{ route('user.products') }}">
                    <i class="fas fa-cart-arrow-down"></i>
                    لیست محصولات
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.service.create.page' ? 'active' : '' }}">
                <a href="{{ route('user.service.create.page') }}">
                    <i class="fas fa-plus"></i>
                    ثبت خدمت
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.services' ? 'active' : '' }}">
                <a href="{{ route('user.services') }}">
                    <i class="fas fa-clipboard-list"></i>
                    لیست خدمات
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.purchase.invoice' ? 'active' : '' }}">
                <a href="{{ route('user.purchase.invoice') }}">
                    <i class="fas fa-file-invoice"></i>
                    فاکتورهای خرید
                </a>
            </li>
            @if(auth()->guard('web')->user()->store()->count() > 0)
                <?php

                if (auth()->guard('web')->user()->store != null)
                    $bill = \App\Bill::where('store_id', auth()->guard('web')->user()->store->id)->where('status', 'pending')->count();
                else
                    $bill = 0;
                ?>
                <li class="list-inline {{ Route::current()->getName() === 'user.purchase.sales' ? 'active' : '' }}">
                    <a href="{{ route('user.purchase.sales') }}">
                        <i class="fas fa-file-invoice"></i>
                        فاکتورهای فروش
                        <span class="btn btn-xs btn-success">{{ $bill }}</span>
                    </a>
                </li>
            @endif

            <?php
            use App\ProductSellerComment;
            use App\ProductSeller;
            if (auth()->guard('web')->user()->store != null) {
                $user = auth()->guard('web')->user();
                $productsId = ProductSeller::join('store' , 'product_seller.store_id' , '=' , 'store.id')->
                where('user_id' , $user->id)->select('product_seller.id as id')->pluck('id')->toArray();
                $comments = ProductSellerComment::whereIn('product_seller_id', $productsId)->where('status', 'pending')->count();
            } else
                $comments = 0;
            ?>
            <li class="list-inline {{ Route::current()->getName() === 'user.comments' ? 'active' : '' }}">
                <a href="{{ route('user.comments') }}">
                    <i class="fas fa-comment"></i>
                    مدیریت نظرات
                    <span class="btn btn-xs btn-success">{{ $comments }}</span>
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'user.accounting.document' ? 'active' : '' }}">
                <a href="{{ route('user.accounting.document') }}">
                    <i class="fa fa-credit-card"></i>
                    تسویه حساب
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'wallet.index' ? 'active' : '' }}">
                <a href="{{ route('panel.wallet.index') }}">
                    <i class="fas fa-wallet"></i> کیف پول
                </a>
            </li>
            
            <li class="list-inline {{ Route::current()->getName() === 'discounts.user.index' ? 'active' : '' }}">
                <a href="{{ route('discounts.user.index') }}">
                    <i class="fa fa-percent"></i> کد های تخفیف
                </a>
            </li>
            <li class="list-inline {{ Route::current()->getName() === 'chats.get' ? 'active' : '' }}">
                <a href="{{ route('chats.get') }}">
                    <i class="fa fa-comment"></i>گفت و گو ها
                    <span class="btn btn-xs btn-success">{{ \App\PrivateMessage::join('chats' , 'private_messages.chat_id' , '=' , 'chats.id')->where('read' , false)
                    ->where(function ($query){
                        $query->where(function($query){
                            $query->where('receiver_id' , auth()->guard('web')->user()->id)->where('is_sent' , true);
                        })->orWhere(function ($query){
                        $query->where('sender_id' , auth()->guard('web')->user()->id)->where('is_sent' , false);
                        });
                    })
                    
                    ->count() + \App\Message::where('user_id' , null)
                    ->where('receiver_id' , auth()->guard('web')->user()->id)
                    ->where('view' , 0)->count() }}</span>
                </a>
            </li>
            @if($auth_web_user->store)
                <li class="list-inline {{ Route::current()->getName() === 'my_account.ads_panel' ? 'active' : '' }}">
                    <a href="{{ route('my_account.ads_panel') }}">
                        <i class="fa fa-audio-description"></i>تبلیغات
                    </a>
                </li>
            @endif
            <li class="list-inline {{ Route::current()->getName() === 'share.index' ? 'active' : '' }}">
                    <a href="{{ route('share.index') }}">
                        <i class="fa fa-share-alt"></i>معرفی برنامه
                    </a>
            </li>
            <li class="list-inline">
                <a href="{{ route('logout.user') }}">
                    <i class="fas fa-power-off"></i>
                    خروج از حساب
                </a>
            </li>

        </ul>
    </div>
</div>