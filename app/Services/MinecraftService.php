<?php

namespace App\Services;

use App\Contracts\LookupService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class MinecraftService implements LookupService
{

    public static function type(): string
    {
        return 'minecraft';
    }

    /**
     * @throws RequestException
     */
    public function lookup(?string $username, ?string $id)
    {
        $url = $this->getUrl(username: $username, id: $id);

        try {
            $response = Http::get($url)->throw();

            $result = json_decode($response->body());

            // todo: cache result?

            return $result;
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    private function getUrl(?string $username, ?string $id): string
    {
        $keyType = $username ? 'username' : 'id';
        $key = $username ?? $id;
        return config('lookup.minecraft.endpoint.' . $keyType) . $key;
    }
}
