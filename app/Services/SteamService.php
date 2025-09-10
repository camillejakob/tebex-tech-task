<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class SteamService extends AbstractLookupService
{
    public string $keyType = 'id';
    public string $key;

    public static function service(): string
    {
        return 'steam';
    }

    protected function endpoint(): string
    {
        return config('lookup.steam.endpoint.id') . $this->key;
    }

    public function cachePrefix(): string
    {
        return config('lookup.services.steam.cacheKey');
    }

    /**
     * @throws ValidationException
     */
    public function lookup(?string $username, ?string $id): mixed
    {
        if ($username) {
            throw ValidationException::withMessages([
                'username' => ['Steam only supports IDs']
            ]);
        }

        return parent::lookup(username: null, id: $id);
    }
}
