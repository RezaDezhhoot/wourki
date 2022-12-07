<?php

namespace Database\Seeders;

use App\UpgradePosition;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // Products
        UpgradePosition::create(['position' => 'product_in_newest' , 'name' => 'در جدیدترین محصولات' , 'price' => 0]);
        UpgradePosition::create(['position' => 'product_in_most_visited' , 'name' => 'در پربازدید ترین محصولات' , 'price' => 0]);
        UpgradePosition::create(['position' => 'product_in_most_sold' , 'name' => 'در پرفروش ترین محصولات' , 'price' => 0]);
        UpgradePosition::create(['position' => 'product_in_page' , 'name' => 'در صفحه محصولات' , 'price' => 0]);
        // Service
        UpgradePosition::create(['position' => 'service_in_newest', 'name' => 'در جدیدترین خدمات', 'price' => 0]);
        UpgradePosition::create(['position' => 'service_in_most_visited', 'name' => 'در پربازدید ترین خدمات', 'price' => 0]);
        UpgradePosition::create(['position' => 'service_in_most_sold', 'name' => 'در پرفروش ترین خدمات', 'price' => 0]);
        UpgradePosition::create(['position' => 'service_in_page', 'name' => 'در صفحه خدمات', 'price' => 0]);
        // Store
        UpgradePosition::create(['position' => 'store_in_newest', 'name' => 'در آخرین فروشگاه ها', 'price' => 0]);
        UpgradePosition::create(['position' => 'store_in_best', 'name' => 'در بهترین فروشگاه ها', 'price' => 0]);
        UpgradePosition::create(['position' => 'store_in_page', 'name' => 'در صفحه فروشگاه ها', 'price' => 0]);
    }
}
