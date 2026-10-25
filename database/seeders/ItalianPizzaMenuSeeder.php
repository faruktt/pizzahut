<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\ProductVariation;
use App\Models\Size;
use App\Models\Crust;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItalianPizzaMenuSeeder extends Seeder
{
    /**
     * Seed the new Italian Pizza & Restaurant Menu from the images.
     */
    public function run(): void
    {
        // 1. Clean existing menu data (respect foreign key constraints)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('deal_products')->truncate();
        DB::table('product_variations')->truncate();
        DB::table('product_sizes')->truncate();
        DB::table('product_crusts')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('sizes')->truncate();
        DB::table('crusts')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Global Sizes
        $sizeRecords = [
            '6"'   => Size::create(['name' => '6"', 'is_active' => true]),
            '9"'   => Size::create(['name' => '9"', 'is_active' => true]),
            '12"'  => Size::create(['name' => '12"', 'is_active' => true]),
            '1:1'  => Size::create(['name' => '1:1', 'is_active' => true]),
            '1:3'  => Size::create(['name' => '1:3', 'is_active' => true]),
            'Half' => Size::create(['name' => 'Half', 'is_active' => true]),
            'Full' => Size::create(['name' => 'Full', 'is_active' => true]),
        ];

        // 3. Create Global Crusts
        $crustRecords = [
            'Thin Crust'           => Crust::create(['name' => 'Thin Crust', 'is_active' => true]),
            'Pan Crust'            => Crust::create(['name' => 'Pan Crust', 'is_active' => true]),
            'Thick Crust'          => Crust::create(['name' => 'Thick Crust', 'is_active' => true]),
            'Cheese Stuffed Crust' => Crust::create(['name' => 'Cheese Stuffed Crust', 'is_active' => true]),
        ];

        // 4. Create Categories
        $categoriesData = [
            'regular-pizza' => [
                'name' => 'Regular Pizza',
                'slug' => 'regular-pizza',
                'image' => 'cat_pizza.png',
                'sort_order' => 1,
            ],
            'italian-pizza-thin' => [
                'name' => 'Italian Pizza (Thin)',
                'slug' => 'italian-pizza-thin',
                'image' => 'cat_pizza.png',
                'sort_order' => 2,
            ],
            'special-pizza-thick' => [
                'name' => 'Special Pizza (Thick)',
                'slug' => 'special-pizza-thick',
                'image' => 'cat_pizza.png',
                'sort_order' => 3,
            ],
            'set-menu' => [
                'name' => 'Delicious Set Menu',
                'slug' => 'set-menu',
                'image' => 'set_menu.jpg',
                'sort_order' => 4,
            ],
            'roll-shawarma' => [
                'name' => 'Roll & Shawarma',
                'slug' => 'roll-shawarma',
                'image' => 'shawarma.jpg',
                'sort_order' => 5,
            ],
            'burger' => [
                'name' => 'Burger',
                'slug' => 'burger',
                'image' => 'burger.jpg',
                'sort_order' => 6,
            ],
            'noodles' => [
                'name' => 'Noodles',
                'slug' => 'noodles',
                'image' => 'noodles.jpg',
                'sort_order' => 7,
            ],
            'sandwich' => [
                'name' => 'Sandwich',
                'slug' => 'sandwich',
                'image' => 'sandwich.jpg',
                'sort_order' => 8,
            ],
            'soup' => [
                'name' => 'Soup',
                'slug' => 'soup',
                'image' => 'soup.jpg',
                'sort_order' => 9,
            ],
            'oven-baked-pasta' => [
                'name' => 'Oven Baked Pasta',
                'slug' => 'oven-baked-pasta',
                'image' => 'pasta.jpg',
                'sort_order' => 10,
            ],
            'fried-chicken-wings' => [
                'name' => 'Fried Chicken & Wings',
                'slug' => 'fried-chicken-wings',
                'image' => 'fried_chicken.jpg',
                'sort_order' => 11,
            ],
            'packages-combos' => [
                'name' => 'Packages & Combos',
                'slug' => 'packages-combos',
                'image' => 'cat_deals.png',
                'sort_order' => 12,
            ],
            'snacks-sides' => [
                'name' => 'Snacks & Sides',
                'slug' => 'snacks-sides',
                'image' => 'meatbox.jpg',
                'sort_order' => 13,
            ],
            'salad' => [
                'name' => 'Salad',
                'slug' => 'salad',
                'image' => 'salad.jpg',
                'sort_order' => 14,
            ],
            'wonton-momo' => [
                'name' => 'Wonton & Momo',
                'slug' => 'wonton-momo',
                'image' => 'wonton_momo.jpg',
                'sort_order' => 15,
            ],
            'fresh-juice' => [
                'name' => 'Fresh Juice',
                'slug' => 'fresh-juice',
                'image' => 'drinks.jpg',
                'sort_order' => 16,
            ],
            'soft-drinks-lassi' => [
                'name' => 'Soft Drinks, Coffee & Lassi',
                'slug' => 'soft-drinks-lassi',
                'image' => 'cat_drinks.png',
                'sort_order' => 17,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $key => $cData) {
            $categories[$key] = Category::create([
                'name' => $cData['name'],
                'slug' => $cData['slug'],
                'image' => $cData['image'],
                'sort_order' => $cData['sort_order'],
                'is_active' => true,
            ]);
        }

        // Helper closures to attach pizza variations
        $attachPizzaVariations = function ($product, $sizePrices, $crustTypes) use ($sizeRecords, $crustRecords) {
            foreach ($sizePrices as $sName => $price) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'name' => $sName,
                    'price' => $price,
                    'sort_order' => ($sName === '6"' ? 1 : ($sName === '9"' ? 2 : 3)),
                ]);

                foreach ($crustTypes as $cName => $extra) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'size_id' => $sizeRecords[$sName]->id,
                        'size_name' => $sName,
                        'crust_id' => $crustRecords[$cName]->id,
                        'crust_name' => $cName,
                        'price' => $price + $extra,
                        'is_active' => true,
                    ]);
                }
            }

            foreach ($crustTypes as $cName => $extra) {
                ProductCrust::create([
                    'product_id' => $product->id,
                    'name' => $cName,
                    'extra_price' => $extra,
                ]);
            }
        };

        // Helper to attach size-only variations (Soup & Pasta)
        $attachSizeOnly = function ($product, $sizePrices) use ($sizeRecords) {
            $order = 1;
            foreach ($sizePrices as $sName => $price) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'name' => $sName,
                    'price' => $price,
                    'sort_order' => $order++,
                ]);

                ProductVariation::create([
                    'product_id' => $product->id,
                    'size_id' => $sizeRecords[$sName]->id,
                    'size_name' => $sName,
                    'crust_id' => null,
                    'crust_name' => null,
                    'price' => $price,
                    'is_active' => true,
                ]);
            }
        };

        // Standard Crusts for Regular & Italian Pizzas
        $regularCrusts = [
            'Pan Crust' => 0,
            'Thin Crust' => 0,
            'Cheese Stuffed Crust' => 150,
        ];
        $thinCrusts = [
            'Thin Crust' => 0,
            'Pan Crust' => 0,
            'Cheese Stuffed Crust' => 150,
        ];
        $thickCrusts = [
            'Thick Crust' => 0,
            'Cheese Stuffed Crust' => 150,
        ];

        // ==========================================
        // 1. REGULAR PIZZA
        // ==========================================
        $regPizzas = [
            [
                'name' => 'Chicken Delight',
                'description' => 'Topped with Chicken, Capsicum, Green Chilli, Onion, Mozzarella Cheese, Oregano',
                'image' => '1791873009_1788261069_PH_Ultimate_Hot_Chicken.webp',
                'prices' => ['6"' => 210, '9"' => 360, '12"' => 460],
            ],
            [
                'name' => 'Tandoori Chicken',
                'description' => 'Topped with Tandoori Chicken, Mozzarella Cheese, Black Olive, Onion, Capsicum, Tomato, Chilli Flakes',
                'image' => '1791872965_1781497670_PH_Tandoori-Sauce.webp',
                'prices' => ['6"' => 210, '9"' => 360, '12"' => 460],
            ],
            [
                'name' => 'Chicken BBQ Mania',
                'description' => 'Topped with BBQ Chicken, Capsicum, Green Chilli, Tomato, Onion, Mozzarella Cheese',
                'image' => 'bbq-chicken_main_07cabffcfcf86604b2ecbd6d3f97b6c4.png',
                'prices' => ['6"' => 210, '9"' => 360, '12"' => 460],
            ],
            [
                'name' => 'Chicken Lovers',
                'description' => 'Topped with Chicken, Mozzarella Cheese, Onion, Capsicum, Chilli, Mushroom',
                'image' => 'chicken-supreme_main_0198de3b549d7b6941ebddda37ab7b1f.png',
                'prices' => ['6"' => 210, '9"' => 360, '12"' => 460],
            ],
        ];

        foreach ($regPizzas as $p) {
            $prod = Product::create([
                'category_id' => $categories['regular-pizza']->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'image' => $p['image'],
                'base_price' => $p['prices']['6"'],
                'is_bestseller' => true,
                'is_active' => true,
            ]);
            $attachPizzaVariations($prod, $p['prices'], $regularCrusts);
        }

        // ==========================================
        // 2. ITALIAN PIZZA (THIN)
        // ==========================================
        $italianPizzas = [
            [
                'name' => 'Pizza Margherita',
                'description' => 'Tomato, Mozzarella Cheese, Oregano',
                'image' => 'classic-margherita_main_0eda48a5b3983c37f6e7f29293254b3e.png',
                'prices' => ['6"' => 200, '9"' => 450, '12"' => 500],
            ],
            [
                'name' => 'Pizza Bolognese',
                'description' => 'Tomato, Mozzarella Cheese, Beef Ground Meat, Green Pepper, Olive',
                'image' => 'beef-lovers_main_36f18f80378353cfbe69d7bdfb8a4a49.png',
                'prices' => ['6"' => 330, '9"' => 490, '12"' => 650],
            ],
            [
                'name' => 'Pizza Italian',
                'description' => 'Tomato, Mozzarella Cheese, Mushroom, Chicken Ground Meat, Green Pepper',
                'image' => 'chicken-supreme_main_0198de3b549d7b6941ebddda37ab7b1f.png',
                'prices' => ['6"' => 330, '9"' => 490, '12"' => 650],
            ],
            [
                'name' => 'Pizza Chicken',
                'description' => 'Tomato, Mozzarella Cheese, Chicken, Olive, Capsicum',
                'image' => 'bbq-chicken_main_07cabffcfcf86604b2ecbd6d3f97b6c4.png',
                'prices' => ['6"' => 330, '9"' => 490, '12"' => 650],
            ],
            [
                'name' => 'Pizza Volcano',
                'description' => 'Tomato, Mozzarella Cheese, Roast Beef, Onion, Capsicum',
                'image' => 'spicy-beef-keema_main_fd342cf3fd61cb33967e2c45621273d1.png',
                'prices' => ['6"' => 330, '9"' => 490, '12"' => 650],
            ],
            [
                'name' => 'Pizza Vegetarian',
                'description' => 'Olive, Tomato, Mozzarella Cheese, Eggplant, Capsicum, Mushroom',
                'image' => 'cat_pizza.png',
                'prices' => ['6"' => 330, '9"' => 490, '12"' => 650],
            ],
        ];

        foreach ($italianPizzas as $p) {
            $prod = Product::create([
                'category_id' => $categories['italian-pizza-thin']->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'image' => $p['image'],
                'base_price' => $p['prices']['6"'],
                'is_bestseller' => false,
                'is_active' => true,
            ]);
            $attachPizzaVariations($prod, $p['prices'], $thinCrusts);
        }

        // ==========================================
        // 3. SPECIAL PIZZA (THICK)
        // ==========================================
        $specialPizzas = [
            [
                'name' => 'Pizza Premizo',
                'description' => 'Extra Mozzarella Cheese, Chicken (BBQ), Beef (BBQ), Mushroom, Capsicum, Olive, Black Olive, Jalapeno, Tomato, Sausage',
                'image' => 'bbq-blast_main_faa82edb919f03fc2b43fc4625c9a2b8.png',
                'prices' => ['6"' => 900, '9"' => 1000, '12"' => 1200],
            ],
            [
                'name' => 'Pizza Supreme',
                'description' => 'Cheese, Chicken, Beef, Black Olive, Mushroom, Capsicum, Salami, B.B.Q. Sauce, Extra Cheese',
                'image' => 'chicken-supreme_main_0198de3b549d7b6941ebddda37ab7b1f.png',
                'prices' => ['6"' => 750, '9"' => 850, '12"' => 950],
            ],
            [
                'name' => 'Pizza Bar.B.Q',
                'description' => 'BBQ Chicken, Mozzarella Cheese, Mushroom, Jalapeno, Extra Cheese',
                'image' => 'bbq-temptation_main_29f7cbd647ee89413fbed603ac55e3b5.png',
                'prices' => ['6"' => 760, '9"' => 800, '12"' => 900],
            ],
        ];

        foreach ($specialPizzas as $p) {
            $prod = Product::create([
                'category_id' => $categories['special-pizza-thick']->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'image' => $p['image'],
                'base_price' => $p['prices']['6"'],
                'is_bestseller' => true,
                'is_active' => true,
            ]);
            $attachPizzaVariations($prod, $p['prices'], $thickCrusts);
        }

        // ==========================================
        // 4. DELICIOUS SET MENU (Image 1)
        // ==========================================
        $setMenus = [
            [
                'name' => 'Set Menu 82',
                'description' => 'Fried Rice, Vegetable, Chicken Masala, Chicken Fry 1 pcs',
                'base_price' => 300,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 83',
                'description' => 'Fried Rice, Chicken Masala, BBQ Chicken 1 pcs',
                'base_price' => 300,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 84',
                'description' => 'Fried Rice, Chicken Fry 1 pcs, Vegetable',
                'base_price' => 260,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 85',
                'description' => 'Fried Rice, Beef Masala, BBQ Chicken 1 pcs',
                'base_price' => 320,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 86',
                'description' => 'Fried Rice, Beef Masala',
                'base_price' => 280,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 87',
                'description' => 'Fried Rice, Vegetable Curry',
                'base_price' => 140,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 88',
                'description' => 'Fried Rice, Chicken Masala',
                'base_price' => 230,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 89',
                'description' => 'Fried Rice, Prawn Masala, Vegetable Curry',
                'base_price' => 330,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 90',
                'description' => 'Fried Rice, Chicken Fry 2 pcs',
                'base_price' => 210,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Set Menu 91',
                'description' => 'Fried Rice, Chicken Fried Rice Mixed',
                'base_price' => 190,
                'image' => 'set_menu.jpg',
            ],
            [
                'name' => 'Rice Bowl',
                'description' => 'Fragrant rice bowl topped with spicy chicken cubes and signature sauce',
                'base_price' => 170,
                'image' => 'set_menu.jpg',
            ],
        ];

        foreach ($setMenus as $item) {
            Product::create([
                'category_id' => $categories['set-menu']->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'image' => $item['image'],
                'base_price' => $item['base_price'],
                'is_bestseller' => true,
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 5. ROLL & SHAWARMA
        // ==========================================
        $rolls = [
            ['name' => 'Chicken Roll', 'description' => 'Tender seasoned chicken wrapped in soft flatbread with house sauce', 'base_price' => 130, 'image' => 'shawarma.jpg'],
            ['name' => 'Beef Roll', 'description' => 'Savory beef filling with fresh onions and signature garlic sauce wrapped in warm roll', 'base_price' => 160, 'image' => 'shawarma.jpg'],
        ];

        foreach ($rolls as $r) {
            Product::create([
                'category_id' => $categories['roll-shawarma']->id,
                'name' => $r['name'],
                'slug' => Str::slug($r['name']),
                'description' => $r['description'],
                'image' => $r['image'],
                'base_price' => $r['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 6. BURGER
        // ==========================================
        $burgers = [
            ['name' => 'Chicken Burger', 'description' => 'Crispy seasoned chicken patty, lettuce, mayo, and fresh burger bun', 'base_price' => 150, 'image' => 'burger.jpg'],
            ['name' => 'Beef Burger', 'description' => 'Juicy flame-grilled beef patty with sliced onion, pickles, and signature burger sauce', 'base_price' => 170, 'image' => 'burger.jpg'],
            ['name' => 'Crispy Chicken Super Burger', 'description' => 'Extra large crispy chicken patty with cheese and special spicy mayo', 'base_price' => 200, 'image' => 'burger.jpg'],
            ['name' => 'Double Patty Beef Burger', 'description' => 'Double loaded beef patties with melted cheese and fresh crisp veggies', 'base_price' => 220, 'image' => 'burger.jpg'],
            ['name' => 'Double Patty Chicken Burger', 'description' => 'Two seasoned chicken patties stacked with cheddar cheese and house burger dressing', 'base_price' => 220, 'image' => 'burger.jpg'],
        ];

        foreach ($burgers as $b) {
            Product::create([
                'category_id' => $categories['burger']->id,
                'name' => $b['name'],
                'slug' => Str::slug($b['name']),
                'description' => $b['description'],
                'image' => $b['image'],
                'base_price' => $b['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 7. NOODLES
        // ==========================================
        $noodles = [
            ['name' => 'Chicken Noodles', 'description' => 'Wok-tossed noodles with shredded chicken, crunchy vegetables, and savory sauce', 'base_price' => 200, 'image' => 'noodles.jpg'],
            ['name' => 'Beef Noodles', 'description' => 'Stir-fried noodles with sliced marinated beef and vegetables', 'base_price' => 230, 'image' => 'noodles.jpg'],
            ['name' => 'Prawn Noodles', 'description' => 'Fresh juicy prawns tossed with noodles and garlic herb seasoning', 'base_price' => 260, 'image' => 'noodles.jpg'],
            ['name' => 'Spicy Noodles Chicken', 'description' => 'Spicy chili chicken tossed with egg noodles and spicy pepper sauce', 'base_price' => 210, 'image' => 'noodles.jpg'],
            ['name' => 'Special Noodles', 'description' => 'Chef special mixed noodles with chicken, beef, prawn, and mixed veggies', 'base_price' => 320, 'image' => 'noodles.jpg'],
            ['name' => 'Mushroom Noodles', 'description' => 'Sautéed fresh button mushrooms tossed with stir-fry noodles', 'base_price' => 230, 'image' => 'noodles.jpg'],
        ];

        foreach ($noodles as $n) {
            Product::create([
                'category_id' => $categories['noodles']->id,
                'name' => $n['name'],
                'slug' => Str::slug($n['name']),
                'description' => $n['description'],
                'image' => $n['image'],
                'base_price' => $n['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 8. SANDWICH
        // ==========================================
        $sandwiches = [
            ['name' => 'Chicken Sandwich', 'description' => 'Shredded chicken mixed with creamy mayo, black pepper in toasted bread', 'base_price' => 100, 'image' => 'sandwich.jpg'],
            ['name' => 'Chicken Cheese Sandwich', 'description' => 'Classic chicken sandwich topped with a slice of melted cheddar cheese', 'base_price' => 120, 'image' => 'sandwich.jpg'],
            ['name' => 'Club Sandwich', 'description' => 'Multi-layer toasted sandwich with chicken, egg, tomato, lettuce, and mayo', 'base_price' => 170, 'image' => 'sandwich.jpg'],
            ['name' => 'Sub Sandwich (Chicken)', 'description' => 'Fresh submarine bread stuffed with grilled chicken strips and salad', 'base_price' => 150, 'image' => 'sandwich.jpg'],
            ['name' => 'BBQ Sub Sandwich (Chicken)', 'description' => 'Smoky BBQ chicken glazed strips in a soft toasted sub bun', 'base_price' => 160, 'image' => 'sandwich.jpg'],
            ['name' => 'Smoked Sandwich (Chicken/Beef)', 'description' => 'Delicately smoked chicken or beef slices with honey mustard in bread', 'base_price' => 140, 'image' => 'sandwich.jpg'],
        ];

        foreach ($sandwiches as $s) {
            Product::create([
                'category_id' => $categories['sandwich']->id,
                'name' => $s['name'],
                'slug' => Str::slug($s['name']),
                'description' => $s['description'],
                'image' => $s['image'],
                'base_price' => $s['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 9. SOUP (Sizes: 1:1: 150, 1:3: 440)
        // ==========================================
        $soups = [
            ['name' => 'Chicken Corn Soup', 'description' => 'Traditional creamy sweet corn soup with egg ribbons and tender chicken'],
            ['name' => 'Thai Soup', 'description' => 'Thick spicy, sour, and savory Thai broth with chicken and fragrant herbs'],
            ['name' => 'Thai Clear Soup', 'description' => 'Light aromatic lemongrass and kaffir lime clear broth with chicken'],
            ['name' => 'Special Clear Soup (Chicken, Mushroom, Veg)', 'description' => 'Healthy clear soup with chicken, mushroom, and fresh garden vegetables'],
        ];

        foreach ($soups as $sp) {
            $prod = Product::create([
                'category_id' => $categories['soup']->id,
                'name' => $sp['name'],
                'slug' => Str::slug($sp['name']),
                'description' => $sp['description'],
                'image' => 'soup.jpg',
                'base_price' => 150,
                'is_active' => true,
            ]);
            $attachSizeOnly($prod, ['1:1' => 150, '1:3' => 440]);
        }

        // ==========================================
        // 10. OVEN BAKED PASTA (Sizes: Half, Full)
        // ==========================================
        $pastas = [
            [
                'name' => 'Italian Pasta',
                'description' => 'Chicken, Capsicum, creamy white sauce topped with baked mozzarella cheese',
                'prices' => ['Half' => 200, 'Full' => 330],
            ],
            [
                'name' => 'Mexican Pasta',
                'description' => 'Minced beef, Capsicum, spicy Mexican chili sauce with melted golden cheese',
                'prices' => ['Half' => 230, 'Full' => 350],
            ],
            [
                'name' => 'Mushroom Pasta',
                'description' => 'Sautéed mushrooms, capsicum, rich cream sauce baked with mozzarella',
                'prices' => ['Half' => 210, 'Full' => 350],
            ],
            [
                'name' => 'Special Pasta',
                'description' => 'Loaded with Beef, Chicken, Capsicum, Mushroom, secret chef sauce, double cheese',
                'prices' => ['Half' => 280, 'Full' => 530],
            ],
        ];

        foreach ($pastas as $pa) {
            $prod = Product::create([
                'category_id' => $categories['oven-baked-pasta']->id,
                'name' => $pa['name'],
                'slug' => Str::slug($pa['name']),
                'description' => $pa['description'],
                'image' => 'pasta.jpg',
                'base_price' => $pa['prices']['Half'],
                'is_bestseller' => true,
                'is_active' => true,
            ]);
            $attachSizeOnly($prod, $pa['prices']);
        }

        // ==========================================
        // 11. FRIED CHICKEN & WINGS
        // ==========================================
        $friedItems = [
            ['name' => 'Fried Chicken (2 Pcs)', 'description' => 'Crispy golden fried chicken 2 pcs', 'base_price' => 130],
            ['name' => 'BBQ Chicken (2 Pcs)', 'description' => 'Smoky glazed BBQ chicken 2 pcs', 'base_price' => 140],
            ['name' => 'Spicy Chicken (2 Pcs)', 'description' => 'Hot and spicy fried chicken 2 pcs', 'base_price' => 140],
            ['name' => 'Chicken Fried (4 Pcs)', 'description' => 'Golden crunchy chicken fry 4 pcs', 'base_price' => 260],
            ['name' => 'Fried Chicken (10 Pcs)', 'description' => 'Family party bucket fried chicken 10 pcs', 'base_price' => 1150],
            ['name' => 'Strip Chicken (4 Pcs with French Fries)', 'description' => 'Boneless chicken strips with crispy french fries', 'base_price' => 260],
            ['name' => 'Chicken Lollipop 4 Pcs', 'description' => 'Crispy chicken wing lollipops 4 pcs', 'base_price' => 160],
            ['name' => 'BBQ Lollipop 4 Pcs', 'description' => 'Smoky BBQ sauce coated chicken lollipops 4 pcs', 'base_price' => 180],
            ['name' => 'Naga Lollipop 4 Pcs', 'description' => 'Extra fiery Naga chili glazed chicken lollipops 4 pcs', 'base_price' => 180],
            ['name' => 'Hot Wings 6 Pcs with French Fries', 'description' => 'Spicy buffalo hot wings 6 pcs served with crispy fries', 'base_price' => 250],
            ['name' => 'BBQ Wings 4 Pcs', 'description' => 'Juicy BBQ wings glazed in signature smoky sauce 4 pcs', 'base_price' => 130],
        ];

        foreach ($friedItems as $fi) {
            Product::create([
                'category_id' => $categories['fried-chicken-wings']->id,
                'name' => $fi['name'],
                'slug' => Str::slug($fi['name']),
                'description' => $fi['description'],
                'image' => 'fried_chicken.jpg',
                'base_price' => $fi['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 12. PACKAGES & COMBOS
        // ==========================================
        $packages = [
            ['name' => 'Package (1)', 'description' => 'Fried Chicken 1 Pcs, French Fries, Soft Drinks', 'base_price' => 220, 'image' => 'fried_chicken.jpg'],
            ['name' => 'Package (2)', 'description' => 'Beef Burger 1 Pcs, French Fries, Soft Drinks', 'base_price' => 270, 'image' => 'burger.jpg'],
            ['name' => 'Package (3)', 'description' => 'Beef Shawarma 1 Pcs, French Fries, Soft Drinks', 'base_price' => 260, 'image' => 'shawarma.jpg'],
            ['name' => 'Package (4)', 'description' => 'Fried Chicken 2 Pcs, French Fries, Soft Drinks', 'base_price' => 300, 'image' => 'fried_chicken.jpg'],
            ['name' => 'Chicken Nugget 6 Pcs with French Fries', 'description' => 'Golden chicken nuggets 6 pcs with french fries', 'base_price' => 280, 'image' => 'fried_chicken.jpg'],
        ];

        foreach ($packages as $pkg) {
            Product::create([
                'category_id' => $categories['packages-combos']->id,
                'name' => $pkg['name'],
                'slug' => Str::slug($pkg['name']),
                'description' => $pkg['description'],
                'image' => $pkg['image'],
                'base_price' => $pkg['base_price'],
                'is_deal' => true,
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 13. SNACKS & SIDES
        // ==========================================
        $snacks = [
            ['name' => 'Nachos', 'description' => 'Crispy tortilla chips loaded with melted cheese sauce, salsa, and jalapeños', 'base_price' => 220, 'image' => 'meatbox.jpg'],
            ['name' => 'Meat Box', 'description' => 'Crispy fries topped with seasoned chicken cubes, melted cheese, and garlic mayo', 'base_price' => 220, 'image' => 'meatbox.jpg'],
            ['name' => 'Garlic Mushroom', 'description' => 'Sautéed fresh button mushrooms in garlic herb butter', 'base_price' => 270, 'image' => 'meatbox.jpg'],
            ['name' => 'French Fries', 'description' => 'Golden crisp salted potato fries', 'base_price' => 150, 'image' => 'meatbox.jpg'],
            ['name' => 'Potato Wedges', 'description' => 'Seasoned skin-on potato wedges baked to crispy perfection', 'base_price' => 160, 'image' => 'meatbox.jpg'],
        ];

        foreach ($snacks as $snk) {
            Product::create([
                'category_id' => $categories['snacks-sides']->id,
                'name' => $snk['name'],
                'slug' => Str::slug($snk['name']),
                'description' => $snk['description'],
                'image' => $snk['image'],
                'base_price' => $snk['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 14. SALAD
        // ==========================================
        $salads = [
            ['name' => 'Chicken Cashew Nut Salad', 'description' => 'Crispy fried chicken, roasted cashew nuts, cucumber, tomato in sweet chili dressing', 'base_price' => 260],
            ['name' => 'Vegetable Salad with Salami', 'description' => 'Fresh garden greens, sliced salami, olives, and vinaigrette', 'base_price' => 140],
        ];

        foreach ($salads as $sl) {
            Product::create([
                'category_id' => $categories['salad']->id,
                'name' => $sl['name'],
                'slug' => Str::slug($sl['name']),
                'description' => $sl['description'],
                'image' => 'salad.jpg',
                'base_price' => $sl['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 15. WONTON & MOMO
        // ==========================================
        $wontons = [
            ['name' => 'Wonton Fried (4 Pcs)', 'description' => 'Golden deep fried wontons with minced meat filling 4 pcs', 'base_price' => 150],
            ['name' => 'Wonton Fried (8 Pcs)', 'description' => 'Golden deep fried wontons with minced meat filling 8 pcs', 'base_price' => 270],
            ['name' => 'Momo (6 Pcs)', 'description' => 'Steamed juicy chicken dumplings with spicy chili dipping sauce 6 pcs', 'base_price' => 170],
            ['name' => 'Momo (10 Pcs)', 'description' => 'Steamed juicy chicken dumplings with spicy chili dipping sauce 10 pcs', 'base_price' => 270],
        ];

        foreach ($wontons as $wm) {
            Product::create([
                'category_id' => $categories['wonton-momo']->id,
                'name' => $wm['name'],
                'slug' => Str::slug($wm['name']),
                'description' => $wm['description'],
                'image' => 'wonton_momo.jpg',
                'base_price' => $wm['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 16. FRESH JUICE
        // ==========================================
        $juices = [
            ['name' => 'Apple Juice', 'description' => 'Freshly extracted crisp apple juice', 'base_price' => 150],
            ['name' => 'Lemon Juice', 'description' => 'Chilled fresh lemon mint juice', 'base_price' => 160],
            ['name' => 'Watermelon Juice', 'description' => 'Sweet chilled seasonal watermelon juice', 'base_price' => 120],
            ['name' => 'Pineapple Juice', 'description' => 'Tangy sweet tropical pineapple juice', 'base_price' => 120],
            ['name' => 'Papaya Juice', 'description' => 'Smooth chilled fresh papaya juice', 'base_price' => 120],
            ['name' => 'Orange Juice', 'description' => 'Vitamin C rich freshly squeezed orange juice', 'base_price' => 120],
        ];

        foreach ($juices as $jc) {
            Product::create([
                'category_id' => $categories['fresh-juice']->id,
                'name' => $jc['name'],
                'slug' => Str::slug($jc['name']),
                'description' => $jc['description'],
                'image' => 'drinks.jpg',
                'base_price' => $jc['base_price'],
                'is_active' => true,
            ]);
        }

        // ==========================================
        // 17. SOFT DRINKS, COFFEE & LASSI
        // ==========================================
        $drinks = [
            ['name' => 'Coffee Hot Regular', 'description' => 'Freshly brewed rich espresso hot coffee', 'base_price' => 100],
            ['name' => 'Hot Coffee Chocolate', 'description' => 'Rich hot mocha coffee with melted chocolate', 'base_price' => 120],
            ['name' => 'Cold Coffee Chocolate', 'description' => 'Blended iced coffee with chocolate syrup and milk', 'base_price' => 120],
            ['name' => 'Cold Coffee (Strawberry / Mango / Vanilla)', 'description' => 'Flavored blended cold coffee with ice cream scoop', 'base_price' => 150],
            ['name' => 'Sweet Lassi', 'description' => 'Traditional chilled sweet yogurt drink', 'base_price' => 100],
            ['name' => 'Shahi Lassi', 'description' => 'Rich royal lassi topped with nuts, saffron, and cream', 'base_price' => 120],
            ['name' => 'Salt Lassi', 'description' => 'Refreshing salted yogurt lassi with roasted cumin', 'base_price' => 100],
        ];

        foreach ($drinks as $dr) {
            Product::create([
                'category_id' => $categories['soft-drinks-lassi']->id,
                'name' => $dr['name'],
                'slug' => Str::slug($dr['name']),
                'description' => $dr['description'],
                'image' => 'drinks.jpg',
                'base_price' => $dr['base_price'],
                'is_active' => true,
            ]);
        }
    }
}
