<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
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

        //カテゴリー2件紐付け
        $categories = Category::take(2)->get(); //上から2件(ファッション,家電)を取得
        $this->item->categories()->attach($categories->pluck('id'));

        //コメント1件追加
        $this->item->comments()->create([
            'user_id' => $this->user->id,
            'content' => 'テストコメント',
        ]);

        $this->item->likedByUsers()->attach($this->user->id);
    }

    //必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    public function test_item_detail_display_all()
    {
        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response->assertStatus(200);
        $response->assertSee('/storage/' . $this->item->image_path);
        $response->assertSeeText('テスト商品');
        $response->assertSeeText('テストブランド');
        $response->assertSeeText('¥5,000');
        $response->assertSeeText('1');
        $response->assertSeeText('1');
        $response->assertSeeText('テストデータ');
        $response->assertSeeText('良好');
        foreach($this->item->categories as $category) {
            $response->assertSeeText($category->content);
        }
        $response->assertSeeText('コメント(1)');
        $response->assertSeeText($this->user->name);
        $response->assertSeeText('テストコメント');
    }

    //複数選択されたカテゴリが表示されているか
    public function test_item_detail_display_categories()
    {
        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response->assertStatus(200);
        foreach($this->item->categories as $category) {
            $response->assertSeeText($category->content);
        }
    }
}
