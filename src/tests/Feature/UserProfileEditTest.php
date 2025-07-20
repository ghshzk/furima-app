<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_path' => 'img/test_icon.jpg',
            'postcode' => '123-4567',
            'address' => '東京都テスト区テスト町1-1-1',
            'building' => 'テストマンション101',
        ]);
    }

    //変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_profile_edit_current_data()
    {
        $response = $this->actingAs($this->user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('test_icon.jpg');
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都テスト区テスト町1-1-1');
        $response->assertSee('テストマンション101');
    }
}
