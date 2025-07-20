<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Order;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyListTest extends TestCase
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

        $this->likedItem = Item::first();
        Like::create([
            'user_id' => $this->user->id,
            'item_id' => $this->likedItem->id,
        ]);
    }

    //いいねした商品だけが表示される
    public function test_my_list_liked_items_display()
    {
        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSeeText($this->likedItem->name);

        foreach ($this->items as $item) {
            if ($item->id !== $this->likedItem->id) {
                $response->assertDontSeeText($item->name);
            }
        }
    }

    //購入済み商品は「Sold」と表示される
    public function test_my_list_sold_items_display_sold_status()
    {
        Order::create([
            'user_id' => $this->user->id,
            'item_id' => $this->likedItem->id,
            'price' => $this->likedItem->price,
            'payment_method' => 2,
            'shipping_address' => 'ダミー住所'
        ]);

        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->likedItem->name,
            'SOLD',
        ]);
    }

    //自分が出品した商品は表示されない
    public function test_my_list_not_show_own_items()
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

        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertDontSeeText('出品商品');
    }

    //未認証の場合は何も表示されない
    public function test_my_list_empty_guests()
    {
        $response = $this->get('/?tab=mylist');

        $this->assertGuest($guard = null);

        $response->assertStatus(200);
        foreach ($this->items as $item) {
            $response->assertDontSeeText($item->name);
        }
    }
}
