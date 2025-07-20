<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
        ]);

        $this->user = User::first();
        $this->items = Item::with('orders')->get();
    }

    //全商品を取得できる
    public function test_list_get_all_items()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('items');

        foreach ($this->items as $item) {
            $response->assertSeeText($item->name);
        }
    }

    //購入済み商品は「Sold」と表示される
    public function test_list_sold_items_display_sold_status()
    {
        $soldItem = $this->items->first();
        Order::create([
            'user_id' => $this->user->id,
            'item_id' => $soldItem->id,
            'price' => $soldItem->price,
            'payment_method' => 2,
            'shipping_address' => 'ダミー住所',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $soldItem->name,
            'SOLD',
        ]);
    }

    //自分が出品した商品は表示されない
    public function test_list_not_show_own_items()
    {
        Item::create([
            'name' => '出品商品',
            'price' => 1000,
            'description' => 'テスト',
            'condition' => 1,
            'image_path' => 'items/item_image_1.jpg',
            'brand' => null,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get('/');

        $response->assertDontSeeText('出品商品');
    }
}
