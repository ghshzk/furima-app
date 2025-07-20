<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
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

    //ログイン済みのユーザーはコメントを送信できる
    public function test_authenticated_user_can_post_comment()
    {
        $response = $this->actingAs($this->user)->post('/item/' . $this->item->id . '/comment', [
            'content' => 'テストコメント',
        ]);

        $response->assertStatus
        (302);
        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'content' => 'テストコメント',
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $response->assertSeeText('コメント(1)');
    }

    //ログイン前のユーザーはコメントを送信できない
    public function test_guest_cannot_post_comment()
    {
        $response = $this->post('/item/' . $this->item->id . '/comment', [
            'content' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'content' => 'ゲストコメント',
        ]);
    }

    //コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_validate_content()
    {
        $response = $this->actingAs($this->user)->post('/item/' . $this->item->id . '/comment', [
            'content' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    //コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_validate_max()
    {
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($this->user)->post('/item/' . $this->item->id . '/comment', [
            'content' => $longComment
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください'
        ]);
    }
}
