<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UsersTableSeeder::class,
        ]);

        $users = User::all();
        $this->user = $users->first();
        $this->otherUser = $users->last();

        $this->itemA = Item::create([
            'name' => 'テスト商品A',
            'price' => '5000',
            'description' => '説明A',
            'condition' => 1,
            'image_path' => 'items/item_img_1.img',
            'brand' => null,
            'user_id' => $this->otherUser->id,
        ]);

        $this->itemB = Item::create([
            'name' => '商品B',
            'price' => '3000',
            'description' => '説明B',
            'condition' => 2,
            'image_path' => 'items/item_img_2.jpg',
            'brand' => null,
            'user_id' => $this->otherUser->id,
        ]);

        $this->user->likedItems()->attach($this->itemA->id);
        $this->user->likedItems()->attach($this->itemB->id);
    }

    //「商品名」で部分一致検索ができる
    public function test_search_items_partial_name_match()
    {
        $response = $this->actingAs($this->user)->get('/?keyword=テスト');

        $response->assertStatus(200);
        $response->assertSeeText('テスト商品A');
        $response->assertDontSeeText('商品B');
    }

    //検索状態がマイリストでも保持されている
    public function test_search_state_persists_mylist()
    {
        $keyword = 'テスト';

        $homeResponse = $this->actingAs($this->user)->get('/?keyword=' . $keyword);
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('value="' . $keyword . '"', false);
        $homeResponse->assertSeeText($keyword);

        $mylistResponse = $this->actingAs($this->user)->get('/?tab=mylist&keyword=' . $keyword);
        $mylistResponse->assertStatus(200);
        $mylistResponse->assertSee('value="' . $keyword . '"', false);
        $mylistResponse->assertSeeText($keyword);
        $mylistResponse->assertDontSeeText('商品B');
    }
}
