<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data banner dummy untuk testing
        $banners = [
            [
                'image' => 'brand1.jpeg',
                'link' => 'https://shoptulola.com/',
                'alt_text' => 'Koleksi Tulola - Brand Partner BTHC',
                'order' => 0,
                'is_active' => true,
            ],
            [
                'image' => 'brand2.jpeg',
                'link' => 'https://shopsunakajewelry.com/',
                'alt_text' => 'Koleksi Sunaka Jewelry - Brand Partner BTHC',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'image' => 'brand3.jpeg',
                'link' => 'https://www.mahija.co/',
                'alt_text' => 'Koleksi Mahija - Brand Partner BTHC',
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
