<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Events\Api\OnUserLogin;
use App\Http\Requests\Api\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::wherePhone($request->input('phone'))->firstOrFail();

        event(new OnUserLogin($user, Carbon::now()));

        return response()->json([
            'status' => Response::HTTP_OK,
        ]);
    }
}
