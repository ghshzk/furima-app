<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemLikeTest extends TestCase
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

        $this->item = Item::create([
            'name' => 'テスト商品',
            'price' => '5000',
            'description' => 'テストデータ',
            'condition' => 1,
            'image_path' => 'items/item_img_1.jpg',
            'brand' => 'テストブランド',
            'user_id' => $this->otherUser->id,
        ]);
    }

    //いいねアイコンを押下することによって、いいねした商品として登録することができる。
    public function test_likes_item_on_click()
    {
        $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response = $this->actingAs($this->user)->put('/item/' . $this->item->id . '/like');
        $response->assertStatus(302);
        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $response->assertSee('<span class="like-form__count">1</span>', false);
    }

    //追加済みのアイコンは色が変化する
    public function test_like_icon_change_color()
    {
        $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $this->actingAs($this->user)->put('/item/' . $this->item->id . '/like');
        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response->assertSee('like-form__btn liked');
    }
    //再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_unlike_item_on_click()
    {
        $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $this->actingAs($this->user)->put('/item/' . $this->item->id . '/like');
        $this->assertDatabaseHas('likes',[
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $response->assertSee('<span class="like-form__count">1</span>', false);

        $this->actingAs($this->user)->put('/item/' . $this->item->id . '/like');
        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $response->assertSee('<span class="like-form__count">0</span>', false);
    }
}
