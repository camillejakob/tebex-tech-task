<?php

namespace App\Services;

use App\Contracts\LookupService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;

class SteamService implements LookupService
{

    public static function type(): string
    {
        return 'steam';
    }

    /**
     * @throws RequestException
     * @throws ValidationException
     */
    public function lookup(?string $username, ?string $id): mixed
    {
        if ($username) {
            throw ValidationException::withMessages([
                'username' => ['Steam only supports IDs']
            ]);
        }

        $url = $this->getUrl(id: $id);

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

    private function getUrl(?string $id): string
    {
        return config('lookup.steam.endpoint.id') . $id;
    }
}
