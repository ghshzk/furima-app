<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\LoginResponse;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        app(AttemptToAuthenticate::class)->handle($request, function($request) {
            app(PrepareAuthenticatedSession::class)->handle($request, function($request) {

            });
        });

        return app(LoginResponse::class);
    }
}
