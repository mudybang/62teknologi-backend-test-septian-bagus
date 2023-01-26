<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'Bakeries',
                'alias' => \Str::slug('Bakeries'),
                'created_at' => now(),
            ],
            [
                'title' => 'Delis',
                'alias' => \Str::slug('Delis'),
                'created_at' => now(),
            ],
            [
                'title' => 'Sandwiches',
                'alias' => \Str::slug('Sandwiches'),
                'created_at' => now(),
            ],
            [
                'title' => 'Soup',
                'alias' => \Str::slug('Soup'),
                'created_at' => now(),
            ],
            [
                'title' => 'Ramen',
                'alias' => \Str::slug('Ramen'),
                'created_at' => now(),
            ],
            [
                'title' => 'Food Stands',
                'alias' => \Str::slug('Food Stands'),
                'created_at' => now(),
            ],
            [
                'title' => 'Middle Eastern',
                'alias' => \Str::slug('Middle Eastern'),
                'created_at' => now(),
            ],
            [
                'title' => 'Halal',
                'alias' => \Str::slug('Halal'),
                'created_at' => now(),
            ],
            [
                'title' => 'Parks',
                'alias' => \Str::slug('Parks'),
                'created_at' => now(),
            ],
            [
                'title' => 'Art Museums',
                'alias' => \Str::slug('Art Museums'),
                'created_at' => now(),
            ],
            [
                'title' => 'Malaysian',
                'alias' => \Str::slug('Malaysian'),
                'created_at' => now(),
            ],
            [
                'title' => 'Vietnamese',
                'alias' => \Str::slug('Vietnamese'),
                'created_at' => now(),
            ],
            [
                'title' => 'Coffee & Tea',
                'alias' => \Str::slug('Coffee & Tea'),
                'created_at' => now(),
            ],
            [
                'title' => 'Desserts',
                'alias' => \Str::slug('Desserts'),
                'created_at' => now(),
            ],
            [
                'title' => 'Tacos',
                'alias' => \Str::slug('Tacos'),
                'created_at' => now(),
            ],
        ];
        Category::insert($data);
    }
}
