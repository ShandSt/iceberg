<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AppendSettingsOptionRequest;
use App\Http\Requests\Api\RefreshSettingsOptionsRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Auth::user()->settings;

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $settings->data,
        ]);
    }

    public function appendOption(AppendSettingsOptionRequest $request): JsonResponse
    {
        /* @var $settings \App\Models\UserSetting */
        $settings = Auth::user()->settings;

        $settings->data = array_merge($settings->data, $request->all());

        $settings->save();

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $settings->data,
        ]);
    }

    public function refreshOptions(RefreshSettingsOptionsRequest $request): JsonResponse
    {
        Auth::user()->settings->data = $request->input();
        Auth::user()->settings->save();

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'settings' => Auth::user()->settings,
            ],
        ]);
    }
}
