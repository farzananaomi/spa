<?php

use App\Customer;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Factory::create();
        Customer::truncate();
        foreach (range(1, 25) as $i) {
            Customer::create(
                [
                    'company' => $faker->company,
                    'email'   => $faker->email,
                    'name'    => $faker->name,
                    'phone'   => $faker->phoneNumber,
                    'address' => $faker->address,
                ]
            );
        }

    }
}
