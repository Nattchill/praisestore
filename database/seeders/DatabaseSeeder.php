<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'David',
            'email'    => 'davidfnatt2002@gmail.com',
            'password' => Hash::make('0778268118'),
            'is_admin' => true,
        ]);

        $categories = [
            ['name' => 'Women', 'slug' => 'women', 'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400'],
            ['name' => 'Men', 'slug' => 'men', 'image' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=400'],
            ['name' => 'Kids', 'slug' => 'kids', 'image' => 'https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=400'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            // Women
            ['category_id' => 1, 'name' => 'Floral Summer Dress', 'price' => 45000, 'old_price' => 60000, 'stock' => 20, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=500', 'description' => 'Beautiful floral summer dress perfect for warm days.'],
            ['category_id' => 1, 'name' => 'Elegant Evening Gown', 'price' => 120000, 'old_price' => null, 'stock' => 10, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1566479179817-c0b5b4b4b4b4?w=500', 'description' => 'Stunning evening gown for special occasions.'],
            ['category_id' => 1, 'name' => 'Casual Linen Blouse', 'price' => 25000, 'old_price' => 32000, 'stock' => 35, 'featured' => false, 'image' => 'https://images.unsplash.com/photo-1485462537746-965f33f7f6a7?w=500', 'description' => 'Comfortable linen blouse for everyday wear.'],
            ['category_id' => 1, 'name' => 'High-Waist Jeans', 'price' => 38000, 'old_price' => null, 'stock' => 25, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500', 'description' => 'Trendy high-waist jeans with a perfect fit.'],
            // Men
            ['category_id' => 2, 'name' => 'Classic Oxford Shirt', 'price' => 35000, 'old_price' => 42000, 'stock' => 30, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500', 'description' => 'Timeless oxford shirt for formal and casual wear.'],
            ['category_id' => 2, 'name' => 'Slim Fit Chinos', 'price' => 42000, 'old_price' => null, 'stock' => 20, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500', 'description' => 'Modern slim fit chinos for a sharp look.'],
            ['category_id' => 2, 'name' => 'Leather Jacket', 'price' => 150000, 'old_price' => 180000, 'stock' => 8, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500', 'description' => 'Premium leather jacket for a bold statement.'],
            ['category_id' => 2, 'name' => 'Casual Polo Shirt', 'price' => 22000, 'old_price' => null, 'stock' => 40, 'featured' => false, 'image' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=500', 'description' => 'Comfortable polo shirt for casual outings.'],
            // Kids
            ['category_id' => 3, 'name' => 'Colorful Kids T-Shirt', 'price' => 12000, 'old_price' => 15000, 'stock' => 50, 'featured' => false, 'image' => 'https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=500', 'description' => 'Fun and colorful t-shirt for kids.'],
            ['category_id' => 3, 'name' => 'Kids Denim Overalls', 'price' => 28000, 'old_price' => null, 'stock' => 15, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1471286174890-9c112ffca5b4?w=500', 'description' => 'Adorable denim overalls for little ones.'],
            // Accessories
            ['category_id' => 4, 'name' => 'Leather Handbag', 'price' => 65000, 'old_price' => 80000, 'stock' => 12, 'featured' => true, 'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500', 'description' => 'Elegant leather handbag for everyday use.'],
            ['category_id' => 4, 'name' => 'Silk Scarf', 'price' => 18000, 'old_price' => null, 'stock' => 25, 'featured' => false, 'image' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=500', 'description' => 'Luxurious silk scarf to complete any outfit.'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, [
                'slug'      => Str::slug($p['name']) . '-' . Str::random(4),
                'is_active' => true,
            ]));
        }
    }
}
