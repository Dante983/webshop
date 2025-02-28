<?php
// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing = Category::where('name', 'Clothing')->first();
        $books = Category::where('name', 'Books')->first();
        $homeGarden = Category::where('name', 'Home & Garden')->first();

        // Electronics products
        $electronicsProducts = [
            [
                'name' => 'Smartphone X',
                'description' => 'Latest smartphone with amazing features',
                'price' => 899.99,
                'stock' => 50,
                'is_featured' => true
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Powerful laptop for professionals',
                'price' => 1299.99,
                'stock' => 25,
                'is_featured' => true
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Premium sound quality earbuds',
                'price' => 149.99,
                'stock' => 100,
                'is_featured' => false
            ],
        ];

        foreach ($electronicsProducts as $product) {
            Product::create([
                'category_id' => $electronics->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'is_featured' => $product['is_featured'],
                'is_active' => true
            ]);
        }

        // Clothing products
        $clothingProducts = [
            [
                'name' => 'Classic T-Shirt',
                'description' => 'Comfortable cotton t-shirt',
                'price' => 24.99,
                'stock' => 200,
                'is_featured' => false
            ],
            [
                'name' => 'Winter Jacket',
                'description' => 'Warm winter jacket with waterproof material',
                'price' => 129.99,
                'stock' => 40,
                'is_featured' => true
            ],
        ];

        foreach ($clothingProducts as $product) {
            Product::create([
                'category_id' => $clothing->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'is_featured' => $product['is_featured'],
                'is_active' => true
            ]);
        }

        // Books products
        $booksProducts = [
            [
                'name' => 'Learn Laravel',
                'description' => 'Comprehensive guide to Laravel framework',
                'price' => 39.99,
                'stock' => 30,
                'is_featured' => true
            ],
            [
                'name' => 'Web Development Basics',
                'description' => 'Introduction to web development',
                'price' => 29.99,
                'stock' => 45,
                'is_featured' => false
            ],
        ];

        foreach ($booksProducts as $product) {
            Product::create([
                'category_id' => $books->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'is_featured' => $product['is_featured'],
                'is_active' => true
            ]);
        }

        // Home & Garden products
        $homeProducts = [
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker for home use',
                'price' => 89.99,
                'stock' => 35,
                'is_featured' => false
            ],
            [
                'name' => 'Garden Tools Set',
                'description' => 'Complete set of tools for your garden',
                'price' => 49.99,
                'stock' => 20,
                'is_featured' => false
            ],
        ];

        foreach ($homeProducts as $product) {
            Product::create([
                'category_id' => $homeGarden->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'is_featured' => $product['is_featured'],
                'is_active' => true
            ]);
        }
    }
}
