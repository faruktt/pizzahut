<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\AddOn;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::firstOrCreate(
            ['email' => 'admin@italianpizza.com'],
            [
                'name' => 'Italian Pizza Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Customer
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Seed Categories
        $categories = [
            ['name' => 'DEALS', 'slug' => 'deals', 'sort_order' => 1],
            ['name' => 'PIZZA', 'slug' => 'pizza', 'sort_order' => 2],
            ['name' => 'MELTS', 'slug' => 'melts', 'sort_order' => 3],
            ['name' => 'PASTA', 'slug' => 'pasta', 'sort_order' => 4],
            ['name' => 'RICE', 'slug' => 'rice', 'sort_order' => 5],
            ['name' => 'SIDES', 'slug' => 'sides', 'sort_order' => 6],
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $created = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
            $catMap[$cat['slug']] = $created->id;
        }

        // 4. Seed Add Ons (matching Screenshot 2)
        $addons = [
            ['name' => 'Cheese', 'icon' => '🧀', 'price' => 249],
            ['name' => 'Chicken', 'icon' => '🍗', 'price' => 159],
            ['name' => 'Spicy Chicken', 'icon' => '🌶️', 'price' => 159],
            ['name' => 'Chicken Sausage', 'icon' => '🌭', 'price' => 159],
            ['name' => 'Beef', 'icon' => '🥩', 'price' => 159],
            ['name' => 'Beef Pepperoni', 'icon' => '🥓', 'price' => 159],
            ['name' => 'Onion', 'icon' => '🧅', 'price' => 89],
            ['name' => 'Fresh Mushrooms', 'icon' => '🍄', 'price' => 99],
            ['name' => 'Green Capsicum', 'icon' => '🫑', 'price' => 79],
            ['name' => 'Black Olives', 'icon' => '🫒', 'price' => 89],
        ];

        foreach ($addons as $addon) {
            AddOn::firstOrCreate(['name' => $addon['name']], $addon);
        }

        // 5. Seed Restaurant Tables (Table 1 to Table 10)
        for ($i = 1; $i <= 10; $i++) {
            RestaurantTable::firstOrCreate(
                ['table_number' => "Table $i"],
                [
                    'capacity' => ($i % 3 == 0) ? 6 : (($i % 2 == 0) ? 2 : 4),
                    'status' => 'available',
                ]
            );
        }

        // 6. Seed Deals (matching Screenshot 1: Our Top Deals)
        $topDeals = [
            [
                'name' => 'Pan 4 All Awesome',
                'slug' => 'pan-4-all-awesome',
                'description' => '4 personal Pizza (Classic Margherita, Classic Chicken Sausage, Sausage & Mushroom Delight, Ultimate Hot Chicken)',
                'base_price' => 999,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => 'Party Box',
                'slug' => 'party-box',
                'description' => '4 Pcs Tandoori Chicken Wings, 1 Cheese Garlic Sticks, 2 Choco Volcano, 1 sweet lemon dip, 1 Bbq dip',
                'base_price' => 1899,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => '1+1 Medium',
                'slug' => '1-plus-1-medium',
                'description' => '2 Medium pizzas at 1,099 taka only',
                'base_price' => 1099,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => 'Double Deal',
                'slug' => 'double-deal',
                'description' => 'Buy 1 Family Pan Pizza & Get 50% off on Second Family Pan Pizza starts from Favorite Line',
                'base_price' => 1599,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => 'Pan 4 All Joy',
                'slug' => 'pan-4-all-joy',
                'description' => '4 personal Pizza (Classic Margherita, Classic Chicken Sausage, Sausage & Mushroom Delight, Chicken Galore)',
                'base_price' => 1199,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => '1+1 Family',
                'slug' => '1-plus-1-family',
                'description' => '2 Family pizzas at 1,999 taka only',
                'base_price' => 1999,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => 'Classic Combo 2',
                'slug' => 'classic-combo-2',
                'description' => '1 Portion Baked Potato Wedges, 2 Pcs Choco Volcano',
                'base_price' => 999,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
            [
                'name' => 'Munch Mix',
                'slug' => 'munch-mix',
                'description' => '1 Cheese Garlic Sticks, 4 pcs Bbq Wings, 1 portion Baked Potato wedges, 1 Sweet Lemon mayo dip',
                'base_price' => 699,
                'category_id' => $catMap['deals'],
                'is_deal' => true,
                'is_bestseller' => false,
            ],
        ];

        foreach ($topDeals as $deal) {
            Product::firstOrCreate(['slug' => $deal['slug']], $deal);
        }

        // 7. Seed Best Seller Pizzas (matching Screenshot 1: Best Seller)
        $pizzas = [
            [
                'name' => 'Chipotle Arrabbiata Blaze (Arrabbiata Sauce)',
                'slug' => 'chipotle-arrabbiata-blaze',
                'description' => 'Smoky chipotle sauce with zesty arrabbiata, rich melted mozzarella, and spiced chicken',
                'base_price' => 1499,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => true,
            ],
            [
                'name' => 'Texas BBQ Smokehouse (BBQ Sauce)',
                'slug' => 'texas-bbq-smokehouse',
                'description' => 'Authentic hickory BBQ sauce, tender shredded meat, caramelized onions, and premium cheese',
                'base_price' => 1499,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => true,
            ],
            [
                'name' => 'Classic Margherita',
                'slug' => 'classic-margherita',
                'description' => 'Classic pizza mozzarella cheese, rich Italian herb tomato sauce, fresh basil sprinkle',
                'base_price' => 599,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Spicy Beef Keema',
                'slug' => 'spicy-beef-keema',
                'description' => 'Traditional spiced minced beef keema, fragrant green chilies, onions, and melted mozzarella',
                'base_price' => 749,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Beef Lovers',
                'slug' => 'beef-lovers',
                'description' => 'Loaded with seasoned ground beef, beef pepperoni, juicy capsicum, onions, and black olives',
                'base_price' => 849,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Bbq Temptation',
                'slug' => 'bbq-temptation',
                'description' => 'Sweet smoky BBQ chicken chunks, diced bell peppers, red onions, and rich mozzarella',
                'base_price' => 949,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Bbq Blast',
                'slug' => 'bbq-blast',
                'description' => 'Bold explosion of BBQ grilled chicken, spicy jalapeños, and triple cheese layer',
                'base_price' => 1099,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Beef Supremo',
                'slug' => 'beef-supremo',
                'description' => 'Supreme beef blend, savory Italian herbs, melted cheddar mozzarella, crispy crust',
                'base_price' => 1099,
                'category_id' => $catMap['pizza'],
                'is_deal' => false,
                'is_bestseller' => true,
                'is_new' => false,
            ],
        ];

        // Standard Sizes and Crusts for each pizza
        foreach ($pizzas as $pData) {
            $prod = Product::firstOrCreate(['slug' => $pData['slug']], $pData);

            // Seed sizes for this pizza
            $base = $prod->base_price;
            ProductSize::firstOrCreate(['product_id' => $prod->id, 'name' => 'Personal'], [
                'price' => round($base * 0.65),
                'sort_order' => 1,
            ]);
            ProductSize::firstOrCreate(['product_id' => $prod->id, 'name' => 'Medium'], [
                'price' => $base,
                'sort_order' => 2,
            ]);
            ProductSize::firstOrCreate(['product_id' => $prod->id, 'name' => 'Family'], [
                'price' => round($base * 1.55),
                'sort_order' => 3,
            ]);
            ProductSize::firstOrCreate(['product_id' => $prod->id, 'name' => 'Large'], [
                'price' => round($base * 1.85),
                'sort_order' => 4,
            ]);

            // Seed crusts for this pizza
            ProductCrust::firstOrCreate(['product_id' => $prod->id, 'name' => 'Pan Crust'], ['extra_price' => 0]);
            ProductCrust::firstOrCreate(['product_id' => $prod->id, 'name' => 'Stuffed Crust'], ['extra_price' => 150]);
            ProductCrust::firstOrCreate(['product_id' => $prod->id, 'name' => 'Sausage Crust'], ['extra_price' => 199]);
        }

        // 8. Seed "You May Also Like" / Sides (matching Screenshot 3)
        $sides = [
            [
                'name' => 'Baked Cheesy Momo',
                'slug' => 'baked-cheesy-momo',
                'description' => 'Steamed dumplings baked under golden mozzarella cheese layer',
                'base_price' => 399,
                'category_id' => $catMap['sides'],
            ],
            [
                'name' => 'Hot BBQ Sauce',
                'slug' => 'hot-bbq-sauce',
                'description' => 'Spicy hickory BBQ dipping sauce',
                'base_price' => 39,
                'category_id' => $catMap['sides'],
            ],
            [
                'name' => 'Dynamite Mayo',
                'slug' => 'dynamite-mayo',
                'description' => 'Creamy spicy dynamite garlic mayonnaise dip',
                'base_price' => 39,
                'category_id' => $catMap['sides'],
            ],
            [
                'name' => 'Spicy Garlic Mushrooms',
                'slug' => 'spicy-garlic-mushrooms',
                'description' => 'Sautéed fresh button mushrooms in spicy garlic butter sauce',
                'base_price' => 549,
                'category_id' => $catMap['sides'],
            ],
        ];

        foreach ($sides as $s) {
            Product::firstOrCreate(['slug' => $s['slug']], $s);
        }

        // 9. Create a Sample Active POS Order on Table 3
        $table3 = RestaurantTable::where('table_number', 'Table 3')->first();
        if ($table3 && Order::where('restaurant_table_id', $table3->id)->count() === 0) {
            $table3->update(['status' => 'occupied']);

            $order = Order::create([
                'order_number' => 'ORD-1001',
                'order_type' => 'dine_in',
                'restaurant_table_id' => $table3->id,
                'customer_name' => 'Dine-in Guest (Table 3)',
                'customer_phone' => '01700000000',
                'subtotal' => 1848,
                'delivery_charge' => 0,
                'vat_tax' => 92.4,
                'discount' => 0,
                'total_amount' => 1940.4,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'order_status' => 'preparing',
                'special_instructions' => 'Extra crispy crust, bring drinks first',
            ]);

            $pizzaProd = Product::where('slug', 'classic-margherita')->first();
            if ($pizzaProd) {
                $item1 = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $pizzaProd->id,
                    'product_name' => 'Classic Margherita',
                    'size_name' => 'Medium',
                    'crust_name' => 'Sausage Crust',
                    'unit_price' => 798,
                    'quantity' => 1,
                    'total_price' => 1047,
                    'special_instructions' => 'Well done',
                ]);

                $item1->addons()->create([
                    'add_on_name' => 'Cheese',
                    'price' => 249,
                ]);
            }

            $momoProd = Product::where('slug', 'baked-cheesy-momo')->first();
            if ($momoProd) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $momoProd->id,
                    'product_name' => 'Baked Cheesy Momo',
                    'size_name' => 'Standard',
                    'crust_name' => 'None',
                    'unit_price' => 399,
                    'quantity' => 2,
                    'total_price' => 798,
                ]);
            }
        }
    }
}
