<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
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

    //小計画面で変更が即時反映される
    public function test_payment_method_update_instantly()
    {
        $this->actingAs($this->user)->get('/purchase/' . $this->item->id);

        $response = $this->followingRedirects()->actingAs($this->user)->put('/purchase/' . $this->item->id, [
            'payment_method' => 'カード支払い',
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('カード支払い');
    }
}
