<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Invoice;
use App\InvoiceItem;

class InvoiceTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Factory::create();
        Invoice::truncate();
        InvoiceItem::truncate();

        foreach (range(1, 25) as $i) {
            $discount=$faker->numberBetween(0,100);
            $sub_total=$faker->numberBetween(500,1000);

            Invoice::create([
                'customer_id' => $i,
                'title'       => $faker->sentence,
                'date'        => '2017-' . mt_rand(1, 12) . '-' . mt_rand(1,28),
                'due_date'    => '2017-' . mt_rand(1, 12) . '-' . mt_rand(1,28),
                'discount'=>$discount,
                'sub_total'=>$sub_total,
                'total'=>$sub_total-$discount,
            ]);
        }

        foreach (range(1,mt_rand(2,6)) as $j){

            InvoiceItem::create([
                'invoice_id'=>$j,
                'description'=>$faker->sentence,
                'quantity'=>$faker->numberBetween(1,7),
                'unit_price'=>$faker->numberBetween(100,400)

            ]);

    }
    }
}
