<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class LookupTest extends TestCase
{
    /** @test */
    public function lookup_minecraft_profile_by_username()
    {
        Http::fake([
            'https://api.mojang.com/users/profiles/minecraft/*' => Http::response([
                'id' => '069a79f444e94726a5befca90e38aaf5',
                'username' => 'Notch',
                'avatar' => 'https://crafatar.com/avatars069a79f444e94726a5befca90e38aaf5',
            ], 200),
        ]);

        $response = $this->getJson('/lookup?type=minecraft&username=Notch');

        $response->assertOk()
            ->assertJson([
                'username' => 'Notch',
                'id'       => '069a79f444e94726a5befca90e38aaf5',
                'avatar'   => 'https://crafatar.com/avatars069a79f444e94726a5befca90e38aaf5',
            ]);
    }

    /** @test */
    public function lookup_minecraft_profile_by_uuid()
    {
        Http::fake([
            'https://sessionserver.mojang.com/session/minecraft/profile/*' => Http::response([
                'id' => '069a79f444e94726a5befca90e38aaf5',
                'username' => 'Notch',
                'avatar'   => 'https://crafatar.com/avatars069a79f444e94726a5befca90e38aaf5',
            ])
        ]);

        $this->getJson('/lookup?type=minecraft&id=069a79f444e94726a5befca90e38aaf5')
            ->assertOk()
            ->assertJson([
                'id' => '069a79f444e94726a5befca90e38aaf5',
                'username' => 'Notch',
                'avatar'   => 'https://crafatar.com/avatars069a79f444e94726a5befca90e38aaf5',
            ]);
    }

    /** @test */
    public function lookup_steam_profile_by_username()
    {
        $this->markTestSkipped();
        HTTP::fake([
            'https://ident.tebex.io/usernameservices/4/username/' => Http::response([
                'id' => '76561198000000000',
                'username' => 'Notch',
                'avatar'   => 'https://crafatar.com/avatars069a79f444e94726a5befca90e38aaf5',
            ])
        ]);

        $this->getJson('/lookup?type=steam&username=Notch')
            ->assertInvalid([
                'username' => 'Steam only supports IDs'
            ]);
    }

    /** @test */
    public function lookup_steam_profile_by_uuid()
    {
        HTTP::fake([
            'https://ident.tebex.io/usernameservices/4/username/*' => Http::response([
                'id' => '76561198000000000',
                'username' => 'jolyne cujoh',
                'avatar'   => 'https://avatars.steamstatic.com/9b07ffdad4718b9b131aa2df88478a8ff45181eb.jpg',
            ])
        ]);

        $this->getJson('/lookup?type=steam&id=76561198000000000')
            ->assertOk()
            ->assertJson([
                'id' => '76561198000000000',
                'username' => 'jolyne cujoh',
                'avatar'   => 'https://avatars.steamstatic.com/9b07ffdad4718b9b131aa2df88478a8ff45181eb.jpg',
            ]);
    }

    /** @test */
    public function lookup_xbl_profile_by_uuid()
    {
        HTTP::fake([
            'https://ident.tebex.io/usernameservices/3/username/' => Http::response([
                'id' => '2535453759792258',
                'username' => 'Notch',
                'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/2535453759792258/avatarpic-l.png',
            ])
        ]);

        $this->getJson('/lookup?type=xbl&id=2535453759792258')
            ->assertOk()
            ->assertJson([
                'id' => '2535453759792258',
                'username' => 'Notch',
                'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/2535453759792258/avatarpic-l.png',
            ]);
    }

    /** @test */
    public function lookup_xbl_profile_by_username()
    {
        HTTP::fake([
            'https://xbl.io/api/v2/profile/xuid/' => Http::response([
                'id' => '2535453759792258',
                'username' => 'Notch',
                'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/2535453759792258/avatarpic-l.png',
            ])
        ]);

        $this->getJson('/lookup?type=xbl&username=Notch')
            ->assertOk()
            ->assertJson([
                'id' => '2535453759792258',
                'username' => 'Notch',
                'avatar'   => 'https://avatar-ssl.xboxlive.com/avatar/2535453759792258/avatarpic-l.png',
            ]);
    }

    /** @test */
    public function lookup_unsupported_type()
    {
        $this->getJson('/lookup?type=psn&username=Notch')
            ->assertInvalid([
                'type' => 'Unsupported type'
            ]);
    }
}
