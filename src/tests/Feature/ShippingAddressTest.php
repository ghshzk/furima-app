<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Services\StripeService;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $stripeMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->stripeMock = Mockery::mock(StripeService::class);
        $this->app->instance(StripeService::class, $this->stripeMock);

        $this->seed([UsersTableSeeder::class,]);

        $this->otherUser = User::first();

        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'postcode' => '123-4567',
            'address' => '東京都テスト区テスト町1-1-1',
            'building' => 'テストマンション101',
        ]);

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

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    //送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_shipping_address_update_reflection()
    {
        $response = $this->actingAs($this->user)->get('/purchase/' . $this->item->id);

        $response->assertSeeText('123-4567');
        $response->assertSeeText('東京都テスト区テスト町1-1-1');
        $response->assertSeeText('テストマンション101');

        $this->actingAs($this->user)->put('/purchase/address/' . $this->item->id, [
            'postcode' => '987-6543',
            'address' => '大阪府テスト市テスト1-1-1',
            'building' => null,
        ])->assertStatus(302);

        $response = $this->actingAs($this->user)->get('/purchase/' . $this->item->id);
        $response->assertStatus(200);
        $response->assertSeeText('987-6543');
        $response->assertSeeText('大阪府テスト市テスト1-1-1');
        $response->assertDontSeeText('テストマンション101');
    }

    //購入した商品に送付先住所が紐づいて登録される
    public function test_shipping_address_link_order()
    {
        $this->stripeMock->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn((object)[
                'url' => '/dummy-checkout-url'
            ]);

        $this->stripeMock->shouldReceive('retrieveCheckoutSession')
            ->once()
            ->with('dummy_session')
            ->andReturn((object)[
                'id' => 'dummy_session',
                'amount_total' => 5000,
                'payment_method_types' => ['card'],
                'metadata' => (object)[
                    'user_id' => $this->user->id,
                    'payment_method' => 'カード支払い',
                    'shipping_address' => '987-6543 大阪府テスト市テスト1-1-1'
                ]
            ]);

        $response = $this->actingAs($this->user)->get('/purchase/' . $this->item->id);

        $response->assertSeeText('123-4567');
        $response->assertSeeText('東京都テスト区テスト町1-1-1');
        $response->assertSeeText('テストマンション101');

        $this->actingAs($this->user)->put('/purchase/address/' . $this->item->id, [
            'postcode' => '987-6543',
            'address' => '大阪府テスト市テスト1-1-1',
            'building' => null,
        ])->assertStatus(302);

        $this->actingAs($this->user)
            ->put('/purchase/order/' . $this->item->id, [
                'payment_method' => 'カード支払い',
                'shipping_address' => '987-6543 大阪府テスト市テスト1-1-1',
            ])
            ->assertRedirect('/dummy-checkout-url');

        $response = $this->actingAs($this->user)->get('/checkout/success?session_id=dummy_session&item_id=' . $this->item->id);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'price' => 5000,
            'payment_method' => 2,
            'shipping_address' => '987-6543 大阪府テスト市テスト1-1-1'
        ]);
    }
}
