<?php

namespace App\Services;

use App\Contracts\LookupService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;

class XblService extends AbstractLookupService
{
    public string $keyType, $key;

    public static function service(): string
    {
        return 'xbl';
    }

    protected function endpoint(): string
    {
        return config('lookup.xbl.endpoint.' . $this->keyType) . $this->key;
    }

    public function cachePrefix(): string
    {
        return config('lookup.services.xbl.cacheKey');
    }
}
