<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'sort_order' => 1
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion items and accessories',
                'sort_order' => 2
            ],
            [
                'name' => 'Books',
                'description' => 'Books, ebooks and publications',
                'sort_order' => 3
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Products for your home and garden',
                'sort_order' => 4
            ],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'sort_order' => $category['sort_order']
            ]);
        }
    }
}
