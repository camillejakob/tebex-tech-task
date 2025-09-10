<?php

namespace App\Services;

class MinecraftService extends AbstractLookupService
{
    public string $keyType, $key;

    public static function service(): string
    {
        return 'minecraft';
    }

    protected function endpoint(): string
    {
        return config('lookup.services.minecraft.endpoint.' . $this->keyType) . $this->key;
    }

    protected function cachePrefix(): string
    {
        return config('lookup.services.minecraft.cacheKey');
    }
}
