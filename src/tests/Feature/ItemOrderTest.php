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

class ItemOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $stripeMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->stripeMock = Mockery::mock(StripeService::class);
        $this->app->instance(StripeService::class, $this->stripeMock);


        $this->seed([UsersTableSeeder::class]);

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

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    //「購入する」ボタンを押下すると購入が完了する
    public function test_order_item_on_click()
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
                    'shipping_address' => '123-1234 東京都新宿区テスト1',
                ]
            ]);

        $this->actingAs($this->user)->get('/purchase/' . $this->item->id)->assertStatus(200);

        $this->actingAs($this->user)
            ->put('/purchase/order/' . $this->item->id, [
                'payment_method' => 'カード支払い',
                'shipping_address' => '123-1234 東京都新宿区テスト1',
            ])
            ->assertRedirect('/dummy-checkout-url');

        $response = $this->actingAs($this->user)->get('/checkout/success?session_id=dummy_session&item_id=' . $this->item->id);
        $response->assertViewIs('checkout_success');

        $response->assertSeeText('決済が完了いたしました');
        $response->assertSeeText('¥5,000');
        $response->assertSeeText('カード支払い');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'price' => 5000,
            'payment_method' => 2,
            'shipping_address' => '123-1234 東京都新宿区テスト1',
        ]);
    }

    //購入した商品は商品一覧画面にて「sold」と表示される
    public function test_order_item_display_sold_status()
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
                    'shipping_address' => '123-567 東京都新宿区テスト1',
                ]
            ]);

        $this->actingAs($this->user)
            ->put('/purchase/order/' . $this->item->id, [
                'payment_method' => 'カード支払い',
                'shipping_address' => '123-1234 東京都新宿区テスト1',
            ])
            ->assertRedirect('/dummy-checkout-url');

        $response = $this->actingAs($this->user)->get('/checkout/success?session_id=dummy_session&item_id=' . $this->item->id);

        $this->item->refresh()->load('orders');

        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->item->name,
            'SOLD',
        ]);
    }

    //「プロフィール/購入した商品一覧」に追加されている
    public function test_order_item_mypage_buy_item_list()
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
                    'shipping_address' => '123-1234 東京都新宿区テスト1'
                ]
            ]);

        $this->actingAs($this->user)
            ->put('/purchase/order/' . $this->item->id, [
                'payment_method' => 'カード支払い',
                'shipping_address' => '123-1234 東京都新宿区テスト1',
            ])
            ->assertRedirect('/dummy-checkout-url');

        $this->actingAs($this->user)->get('/checkout/success?session_id=dummy_session&item_id=' . $this->item->id);

        $response = $this->actingAs($this->user)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee('item_img_1.jpg');
        $response->assertSeeText('テスト商品');
    }
}
