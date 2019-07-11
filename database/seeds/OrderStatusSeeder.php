<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //for user / frontend
        \App\Models\OrderStatus::create(['key' => 'order', 'name' => 'Order Placed']);
        \App\Models\OrderStatus::create(['key' => 'cancel', 'name' => 'Cancelled']);
        \App\Models\OrderStatus::create(['key' => 'confirm', 'name' => 'Confirmed']);

        //for merchant / backend
        \App\Models\OrderStatus::create(['key' => 'hold', 'name' => 'On hold']);
        \App\Models\OrderStatus::create(['key' => 'stock', 'name' => 'Out of Stock']);
        \App\Models\OrderStatus::create(['key' => 'deliver', 'name' => 'Delivered']);
        \App\Models\OrderStatus::create(['key' => 'process', 'name' => 'Processing']);
        \App\Models\OrderStatus::create(['key' => 'dispatch', 'name' => 'Dispatched']);

        \App\Models\OrderStatus::create(['key' => 'complete', 'name' => 'Completed']);
    }
}
