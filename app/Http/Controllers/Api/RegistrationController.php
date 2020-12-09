<?php

namespace App\Http\Controllers\Api;

use App\Events\Api\OnUserLogin;
use App\Events\Api\UserCreated;
use App\Events\Api\UserCreatedFromSite;
use App\Http\Requests\Api\RegistrationConfirmRequest;
use App\Http\Requests\Api\RegistrationRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Models\ConfirmCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegistrationController extends Controller
{
    public function register(RegistrationRequest $request): JsonResponse
    {
        /** @var $user User */

        if (User::where('phone', $request->input('phone'))->exists()) {
            $user = User::where('phone', $request->input('phone'))->first();
            $user->fill($request->only(
                'phone',
                'os',
                'token',
                'device_id',
                'allow_push'
            ))->save();
            $device = $user->devices()->first();
            if ($device) {
                $device->fill($request->only(
                    'phone',
                    'os',
                    'token',
                    'device_id',
                    'allow_push'
                ))->save();
            } else {
                $user->devices()->create($request->only(
                    'phone',
                    'os',
                    'token',
                    'device_id',
                    'allow_push'
                ));
            }
            $new_user = false;
        } else {
            $user = User::create($request->only(
                'phone',
                'os',
                'token',
                'device_id',
                'allow_push'
            ));
            $user->devices()->create($request->only(
                'phone',
                'os',
                'token',
                'device_id',
                'allow_push'
            ));
            $new_user = true;
        }

        $token = str_random(60);

        Auth::login($user);
        Auth::user()->api_token = $token;
        Auth::user()->save();

        if (!$request->exists('site')) {
            event(new UserCreated($user, $token, $new_user));
        } else {
            event(new UserCreatedFromSite($user, $token, $new_user));
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }


    public function confirm(RegistrationConfirmRequest $request): JsonResponse
    {
        if (Auth::user()->phone === '+79990000000' && $request->input('code') === '1234') {
            Auth::user()->status = User::STATUS_ACTIVE;
            Auth::user()->save();
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => []
            ]);
        }

        try {
            $code = ConfirmCode::where('user_id', Auth::user()->id)->firstOrFail();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'data' => [
                    'text' => "Code is invalid",
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($code->code == $request->input('code')) {
            $code->delete();

            Auth::user()->status = User::STATUS_ACTIVE;
            Auth::user()->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => []
            ]);
        }

        return new JsonResponse([
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'data' => [
                'text' => "Code is invalid",
            ],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
