<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //ユーザー作成アクションの指定
        Fortify::createUsersUsing(CreateNewUser::class);

        //会員登録画面のカスタマイズ
        Fortify::registerView(function(){
            return view('auth.register');
        });

        //ログイン画面のカスタマイズ
        Fortify::loginView(function(){
            return view('auth.login');
        });

        //プロフィール更新アクションの指定
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        //ログイン時のレートリミット
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(10)->by($throttleKey);
        });

    }
}
