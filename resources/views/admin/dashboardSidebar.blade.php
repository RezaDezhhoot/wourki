<ul class="aside-user-panel user-panel-list">
    <li tabindex="1" class="my-profile-tab">
        <a class="#" href="x}">
            <div class="text-center">
                <i class="material-icons">account_circle</i>
            </div>
            <p class="text-center">پروفایل من</p>
        </a>
    </li>
    <li tabindex="1" class="my-password-tab">
        <a class="{{ Route::current()->getName() == 'showAddressesPage' ? 'active' : '' }}" href="{{ route('showAddressesPage') }}">
            <div class="text-center">
                <i class="material-icons">location_on</i>
            </div>
            <p class="text-center">آدرسهای من</p>

        </a>
    </li>
    <li tabindex="1" class="my-shop-tab">
        <a class="{{ Route::current()->getName() == 'showStoreEditPage' ? 'active' : '' }}" href="{{ route('showStoreEditPage') }}">
            <div class="text-center">
                <i class="material-icons">store</i>
            </div>
            <p class="text-center"> فروشگاه من</p>

        </a>
    </li>
    <li tabindex="1" class="adding-products">
        <a class="{{ Route::current()->getName() == 'showProductsCreatePage' ? 'active' : '' }}" href="{{ route('showProductsCreatePage') }}">
            <div class="text-center">
                <i class="material-icons">add_shopping_cart</i>
            </div>
            <p style="font-size: 13px" class="text-center">اضافه کردن محصولات</p>

        </a>
    </li>
    <li tabindex="1" class="my-products-tab">
        <a href="{{ route('showVitrinPage') }}" class="{{ Route::current()->getName() == 'showVitrinPage' ? 'active' : '' }}">
            <div class="text-center">
                <i class="material-icons">import_contacts</i>
            </div>
            <p class="text-center">ویترین</p>

        </a>
    </li>
    <li tabindex="1" class="my-in-receipt-tab">
        <a href="{{ route('showAllInFactors') }}" class="{{ Route::current()->getName == 'showAllInFactors'  ? 'active' : '' }}">
            <div class="text-center">
                <i class="material-icons">receipt</i>
            </div>
            @php
            $bills = new \App\Bill();
            $inBillsCount = $bills->select(\App\Bill::FIELDS)
                ->where('bill.store_id', '=', Auth::user()->store->id)->count();
            @endphp
            <p style="font-size: 13px" class="text-center">فاکتور های دریافتی
                @if($inBillsCount > 0 )
                    <span class="label label-primary label-fill">{{ $inBillsCount }}</span>
                @endif
            </p>
        </a>
    </li>
    <li tabindex="1" class="my-out-receipt-tab">
        <a>
            @php
                $bill = new \App\Bill();
           $allBillsCount = $bill->select(\App\Bill::FIELDS)
        ->where('bill.user_id', '=', Auth::user()->id)
        ->orderBy('bill.created_at', 'desc')->count();

            @endphp

            @php
                $bills = new \App\WholeSellerBill();
            $wholeSellerBillsCount = $bills->select( \App\WholeSellerBill::FIELDS)
        ->where('whole_seller_bill.user_id', '=', Auth::user()->id)->count();
            @endphp
            <div class="text-center">
                <i class="material-icons">receipt</i>
            </div>
            <p style="font-size: 13px" class="text-center">فاکتور های خرید
                @if(($wholeSellerBillsCount + $allBillsCount) > 0 )
                    <span class="label label-primary label-fill">{{ $wholeSellerBillsCount + $allBillsCount }}</span>
                @endif
            </p>

        </a>
        <div class="sub-side-bar">
            <ul>

                <li><a href="{{ route('showAllOutFactors') }}" class="{{ Route::current()->getName() === 'showAllOutFactors' ? 'active' : '' }}">فاکتورهای خرده فروش
                        @if($allBillsCount > 0 )
                            <span class="label label-primary label-fill">{{ $allBillsCount }}</span>
                        @endif
                    </a></li>
                <li><a href="{{ route('showWholeSellerBillForRetailers') }}" class="{{ Route::current()->getName() === 'showWholeSellerBillForRetailers' ? 'active' : '' }}">فاکتورهای عمده فروش
                        @if($wholeSellerBillsCount > 0 )
                            <span class="label label-primary label-fill">{{ $wholeSellerBillsCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li tabindex="1" class="logout-tab">
        <a href="{{ route('showMyFavorites') }}" class="{{ Route::current()->getName == 'showMyFavorites'  ? 'active' : '' }}">
            <div class="text-center">
                <i class="material-icons">favorite</i>
            </div>
            <p class="text-center">علاقه مندی ها</p>

        </a>
    </li>
    <li tabindex="1" class="logout-tab">
        <a href="{{ route('doLogout') }}">
            <div class="text-center">
                <i class="material-icons">input</i>
            </div>
            <p class="text-center">خروج</p>

        </a>
    </li>
</ul>
