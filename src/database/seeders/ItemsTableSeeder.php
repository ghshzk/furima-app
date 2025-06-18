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

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000 ,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'item_img_1.jpg',
                'condition' => 1, //良好
                'categories' => ['ファッション', 'メンズ', 'アクセサリー']
            ],
            [
                'name' => 'HDD',
                'price' => 5000 ,
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'item_img_2.jpg',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['家電']
            ],
            [
                'name' => '玉ねぎ３束',
                'price' => 300 ,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'item_img_3.jpg',
                'condition' => 3, //やや傷や汚れあり
                'categories' => ['キッチン']
            ],
            [
                'name' => '革靴',
                'price' => 4000 ,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'item_img_4.jpg',
                'condition' => 4, //状態が悪い
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000 ,
                'description' => '高性能なノートパソコン',
                'image_path' => 'item_img_5.jpg',
                'condition' => 1, //良好
                'categories' => ['家電', 'ゲーム']
            ],
            [
                'name' => 'マイク',
                'price' => 8000 ,
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'item_img_6.jpg',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバック',
                'price' => 3500 ,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'item_img_7.jpg',
                'condition' => 3, //やや傷や汚れあり
                'categories' => ['ファッション', 'レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500 ,
                'description' => '使いやすいタンブラー',
                'image_path' => 'item_img_8.jpg',
                'condition' => 4, //状態が悪い
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000 ,
                'description' => '手動のコーヒーミル',
                'image_path' => 'item_img_9.jpg',
                'condition' => 1, //良好
                'categories' => ['インテリア','キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500 ,
                'description' => '便利なメイクアップセット',                //'image_path' => 'public/img/item_img/item_img_10.jpg',
                'image_path' => 'item_img_10.jpg',
                'condition' => 2, //目立った傷や汚れなし
                'categories' => ['コスメ']
            ],
        ];

        foreach ($items as $index => $item) {
            $fileName = $item['image_path'];
            $sourcePath = public_path('img/item_img/' . $fileName);
            $storagePath = 'items/' . $fileName;

            Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

            $createdItem = Item::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image_path' =>  $storagePath,
                'condition' => $item['condition'],
                'brand' => Str::title(Str::random(rand(6,10))),
                'user_id' => $userIds[array_rand($userIds)]
            ]);

            $categoryIds = array_map(fn($category) => $categories[$category], $item['categories']);
            $createdItem->categories()->attach($categoryIds);
        }
    }
}
