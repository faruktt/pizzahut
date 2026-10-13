<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Combo;
use App\Models\Product;

// Create 2 sample combos
$products = Product::where('is_active', true)->take(6)->get();

// Combo 1
$combo1 = Combo::create([
    'name'        => 'Family Feast Combo',
    'description' => 'Choose any 2 of our signature pizzas at a special price!',
    'price'       => 1599,
    'max_select'  => 2,
    'is_active'   => true,
    'sort_order'  => 1,
]);
$pivot1 = [];
foreach ($products->take(4) as $i => $p) {
    $pivot1[$p->id] = ['sort_order' => $i];
}
$combo1->products()->sync($pivot1);

// Combo 2
$combo2 = Combo::create([
    'name'        => 'Party Box Combo',
    'description' => 'Pick any 3 of your favourites for a big party night!',
    'price'       => 2499,
    'max_select'  => 3,
    'is_active'   => true,
    'sort_order'  => 2,
]);
$pivot2 = [];
foreach ($products->take(6) as $i => $p) {
    $pivot2[$p->id] = ['sort_order' => $i];
}
$combo2->products()->sync($pivot2);

echo "Created 2 combos successfully!\n";
echo "Combo 1 ID: " . $combo1->id . " - " . $combo1->name . "\n";
echo "Combo 2 ID: " . $combo2->id . " - " . $combo2->name . "\n";
