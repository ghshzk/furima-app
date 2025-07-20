<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSellTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);

        $this->user = User::first();
        $this->category = Category::first();
    }

    //商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
    public function test_sell_item_all_required_details()
    {
        Storage::fake('public');

        $this->actingAs($this->user)->get('/sell')->assertStatus(200);

        $filePath = public_path('img/item_img/item_img_1.jpg');
        $uploadedFile = new UploadedFile(
            $filePath,
            'item_img_1.jpg',
            mime_content_type($filePath),
            null,
            true
        );

        $response = $this->followingRedirects()
            ->actingAs($this->user)
            ->post('/sell', [
                'name' => 'テスト商品',
                'price' => '10000',
                'description' => 'テスト出品',
                'condition' => '1',
                'image_path' => $uploadedFile,
                'brand' => 'test-brand',
                'categories' => $this->category->id,
            ]);

        $response->assertStatus(200);
        $item = Item::where('name', 'テスト商品')->first();
        $this->assertNotNull($item);

        $response = $this->actingAs($this->user)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertSeeText('テスト商品');
        $response->assertSeeText('¥10,000');
        $response->assertSeeText('テスト出品');
        $response->assertSeeText('test-brand');
        $response->assertSeeText('良好');
        $response->assertSeeText($this->category->name);
    }
}
