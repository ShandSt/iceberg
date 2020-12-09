<?php

namespace Tests\Feature;

use App\Models\UserSetting;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingsTest extends TestCase
{
    use DatabaseTransactions;


    public function testCreateUserSettingsAfterUserCreating()
    {
        $user = factory(User::class)->create();

        $this->assertNotNull(
            UserSetting::where('user_id', $user->id)->first()
        );
    }

    public function testGetSettings()
    {
        $user = factory(User::class)->create();

        $data = [];

        foreach (['firstOption', 'secondOption'] as $option) {
            $data[$option] = random_int(100,200);
        }
        $user->settings->data = $data;
        $user->settings->save();

        $this->getJson(route('settings.index'), $this->apiHeaders($user->api_token))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => array_keys($data),
            ])->assertJson([
                'status' => Response::HTTP_OK,
                'data' => $data,
            ]);
    }

    public function testAppendSettingOption()
    {
        $user = factory(User::class)->create();

        $data = [];

        foreach (['firstOption', 'secondOption'] as $option) {
            $data[$option] = random_int(100,200);
        }
        $user->settings->data = $data;
        $user->settings->save();

        $this->postJson(route('settings.append_option'),[
            'key' => 'thirdOption',
            'value' => 'someOptionValue',
        ], $this->apiHeaders($user->api_token))->assertStatus(200)
            ->assertJson([
                'status' => Response::HTTP_OK,
                'data' => []
            ]);
    }

    public function testRefreshSettings()
    {
        $user = factory(User::class)->create();

        $data = [];

        foreach (['firstOption', 'secondOption'] as $option) {
            $data[$option] = random_int(100,200);
        }
        $user->settings->data = $data;
        $user->settings->save();

        $this->putJson(route('settings.refresh_options'),[
            [
                'key' => 'thirdOption',
                'value' => 'SomeOptionValue',
            ]
        ], $this->apiHeaders($user->api_token))
            ->assertStatus(200);
    }


}
