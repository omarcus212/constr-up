<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Notebook Dell Inspiron',
                'description' => 'Notebook Dell Inspiron 15 com Intel Core i5, 8GB RAM, SSD 256GB',
                'brand' => 'Dell',
                'price' => 3499.90,
                'stock' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mouse Logitech MX Master',
                'description' => 'Mouse sem fio ergonômico com alta precisão',
                'brand' => 'Logitech',
                'price' => 399.90,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teclado Mecânico Razer',
                'description' => 'Teclado mecânico RGB com switches Cherry MX',
                'brand' => 'Razer',
                'price' => 799.90,
                'stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monitor LG UltraWide',
                'description' => 'Monitor 29 polegadas 2560x1080 IPS',
                'brand' => 'LG',
                'price' => 1299.90,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Webcam Logitech C920',
                'description' => 'Webcam Full HD 1080p com microfone estéreo',
                'brand' => 'Logitech',
                'price' => 549.90,
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}