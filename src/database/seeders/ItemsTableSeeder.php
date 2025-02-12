<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id')->toArray();

        $categories = Category::whereIn('content',[
            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', 'ゲーム', 'キッチン', 'アクセサリー'
        ])->pluck('id','content')->toArray();

        $imageFiles = [
            'item_image_1.jpg',
            'item_image_2.jpg',
            'item_image_3.jpg',
            'item_image_4.jpg',
            'item_image_5.jpg',
            'item_image_6.jpg',
            'item_image_7.jpg',
            'item_image_8.jpg',
            'item_image_9.jpg',
            'item_image_10.jpg',
        ];


        $items = [
            [
                'name' => '腕時計',
                'price' => 15000 ,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => 1, //良好
                'categories' => ['ファッション', 'メンズ', 'アクセサリー']
            ],
            [
                'name' => 'HDD',
                'price' => 5000 ,
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['家電']
            ],
            [
                'name' => '玉ねぎ３束',
                'price' => 300 ,
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 3, //やや傷や汚れあり
                'categories' => ['キッチン']
            ],
            [
                'name' => '革靴',
                'price' => 4000 ,
                'description' => 'クラシックなデザインの革靴',
                'condition' => 4, //状態が悪い
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000 ,
                'description' => '高性能なノートパソコン',
                'condition' => 1, //良好
                'categories' => ['家電', 'ゲーム']
            ],
            [
                'name' => 'マイク',
                'price' => 8000 ,
                'description' => '高音質のレコーディング用マイク',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバック',
                'price' => 3500 ,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 3, //やや傷や汚れあり
                'categories' => ['ファッション', 'レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500 ,
                'description' => '使いやすいタンブラー',
                'condition' => 4, //状態が悪い
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000 ,
                'description' => '手動のコーヒーミル',
                'condition' => 1, //良好
                'categories' => ['インテリア','キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500 ,
                'description' => '便利なメイクアップセット',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['コスメ']
            ],
        ];

        foreach ($items as $index => $item) {
            $imagePath = isset($imageFiles[$index]) ? "storage/items/" . $imageFiles[$index] : null;

            $createdItem = Item::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image_path' =>  $imagePath,
                'condition' => $item['condition'],
                'brand' => Str::title(Str::random(rand(6,10))),
                'user_id' => $userIds[array_rand($userIds)]
            ]);

            $categoryIds = array_map(fn($category) => $categories[$category], $item['categories']);
            $createdItem->categories()->attach($categoryIds);
        }
    }
}
