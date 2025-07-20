<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setup();

        $this->seed([
            UsersTableSeeder::class,
        ]);

        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_path' => 'img/test_icon.jpg'
        ]);

        $this->otherUser = User::first();

        $this->item = Item::create([
            'name' => 'テスト出品商品',
            'price' => '5000',
            'description' => '出品商品説明',
            'condition' => 1,
            'image_path' => 'items/item_img_1.jpg',
            'brand' => 'テストブランド',
            'user_id' => $this->user->id,
        ]);

        $this->orderItem = Item::create([
            'name' => 'テスト購入商品',
            'price' => '10000',
            'description' => '購入商品説明',
            'condition' => 1,
            'image_path' => 'items/item_img_2.jpg',
            'brand' => null,
            'user_id' => $this->otherUser->id,
        ]);

        Order::create([
            'user_id' => $this->user->id,
            'item_id' => $this->orderItem->id,
            'price' => $this->orderItem->price,
            'payment_method' => 2,
            'shipping_address' => 'ダミー住所',
        ]);
    }

    //必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_profile_display_required()
    {
        $response = $this->actingAs($this->user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('test_icon.jpg');
        $response->assertSeeText('テストユーザー');
        $response->assertSeeText('テスト出品商品');

        $response = $this->get('/mypage?tab=buy');
        $response->assertSeeText('テスト購入商品');
    }
}
