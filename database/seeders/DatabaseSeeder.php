<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;
use App\Models\Brand;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'password'=> bcrypt('Fuka_Wata123'),
            'contact'=>'08788789892',
            'hak_akses'=>'Administrator'
        ]);
             // Seed Categories
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Fashion Pria', 'slug' => 'fashion-pria'],
            ['name' => 'Fashion Wanita', 'slug' => 'fashion-wanita'],
            ['name' => 'Rumah Tangga', 'slug' => 'rumah-tangga'],
            ['name' => 'Kesehatan & Kecantikan', 'slug' => 'kesehatan-kecantikan'],
            ['name' => 'Olahraga', 'slug' => 'olahraga'],
            ['name' => 'Mainan & Hobi', 'slug' => 'mainan-hobi'],
        ];

        foreach ($categories as $category) {
            Categorie::create($category);
        }

        // Seed Brands
        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Unbranded', 'slug' => 'unbranded'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    
    }
}
