<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds =User::plunk('id')->toArray();

        $conditions = ['良好', '目立った傷や汚れなし', 'やや傷や汚れあり','状態が悪い'];

        $categories = Category::whereIn('content',[
            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', 'ゲーム', 'キッチン', 'アクセサリー'
        ])->plunk('id','content')->toArray();


        $items = [
            [
                'name' => '腕時計',
                'price' => 15000 ,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'categories' => ['ファッション', 'メンズ', 'アクセサリー']
            ],
            [
                'name' => 'HDD',
                'price' => 5000 ,
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'categories' => ['家電']
            ],
            [
                'name' => '玉ねぎ３束',
                'price' => 300 ,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'categories' => ['キッチン']
            ],
            [
                'name' => '革靴',
                'price' => 4000 ,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000 ,
                'description' => '高性能なノートパソコン',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'categories' => ['家電', 'ゲーム']
            ],
            [
                'name' => 'マイク',
                'price' => 8000 ,
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバック',
                'price' => 3500 ,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'categories' => ['ファッション', 'レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500 ,
                'description' => '使いやすいタンブラー',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000 ,
                'description' => '手動のコーヒーミル',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'categories' => ['インテリア','キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500 ,
                'description' => '便利なメイクアップセット',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'categories' => ['コスメ']
            ],
        ];

        foreach ($items as $item) {
            $imageContents = Http::get($item['image_path'])->body();
            $imageName = 'products/' . Str::random(10) . '.jpg';
            Storage::disk('public')->put($imageName, $imageContents);

            $brandName = Str::ucfirst(Str::random(rand(6,10)));

            $createdItem = Item::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image_path' => $imageName,
                'condition' => $conditions[array_rand($conditions)],
                'brand' => $brandName,
                'user_id' => $userIds[array_rand($useIds)]
            ]);

            $categoryIds = array_map(fn($category) => $categories[$category], $item['categories']);
            $createdItems->categories()->attach($categoryIds);
        }
    }
}
