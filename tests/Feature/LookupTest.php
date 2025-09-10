<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class LookupTest extends TestCase
{
    /** @test */
    public function lookup_minecraft_profile_by_username()
    {
        Http::fake(
            Http::response([
                'id' => '123',
                'username' => 'johnd',
            ])
        );

        $response = $this->getJson('/lookup?type=minecraft&username=johnd');

        $response->assertOk()
            ->assertJson(['data' =>[
                'id'       => '123',
                'username' => 'johnd',
                'avatar'   => 'https://crafatar.com/avatars123',
            ]]);
    }

    /** @test */
    public function lookup_minecraft_profile_by_uuid()
    {
        Http::fake(
            Http::response([
                'id' => '123',
                'username' => 'johnd',
            ])
        );

        $this->getJson('/lookup?type=minecraft&id=123')
            ->assertOk()
            ->assertJson(['data' => [
                'id' => '123',
                'username' => 'johnd',
            ]]);
    }

    /** @test */
    public function lookup_steam_profile_by_username()
    {
        $this->getJson('/lookup?type=steam&username=Notch')
            ->assertInvalid([
                'username' => 'Steam only supports IDs'
            ]);

        Http::assertNothingSent();
    }

    /** @test */
    public function lookup_steam_profile_by_uuid()
    {
        Http::fake(
            Http::response([
                'id' => '123',
                'username' => 'johnd',
                'meta' => [
                    'avatar' => 'https://avatars.steamstatic.com/123.jpg',
                ],
            ])
        );

        $this->getJson('/lookup?type=steam&id=123')
            ->assertOk()
            ->assertJson([
                    'data' => [
                        'id' => '123',
                        'username' => 'johnd',
                        'avatar'   => 'https://avatars.steamstatic.com/123.jpg',
                    ]
                ]
            );
    }

    /** @test */
    public function lookup_xbl_profile_by_uuid()
    {
        Http::fake(
            Http::response([
                'id' => '123',
                'username' => 'johnd',
                'meta' => [
                    'avatar' => 'https://avatar-ssl.xboxlive.com/avatar/123/avatarpic-l.png',
                ],
            ])
        );

        $this->getJson('/lookup?type=xbl&id=123')
            ->assertOk()
            ->assertJson([
                'data' =>
                    [
                        'id' => '123',
                        'username' => 'johnd',
                        'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/123/avatarpic-l.png',
                    ]
                ]);
    }

    /** @test */
    public function lookup_xbl_profile_by_username()
    {
        HTTP::fake(
            Http::response([
                'id' => '123',
                'username' => 'johnd',
                'meta' => [
                    'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/123/avatarpic-l.png',
                ],
            ])
        );

        $this->getJson('/lookup?type=xbl&username=johnd')
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => '123',
                    'username' => 'johnd',
                    'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/123/avatarpic-l.png',
                ]
            ]);

    }

    /** @test */
    public function lookup_unsupported_type()
    {
        $this->getJson('/lookup?type=psn&username=johnd')
            ->assertInvalid([
                'type' => 'The selected type is invalid.',
            ]);
    }
}
