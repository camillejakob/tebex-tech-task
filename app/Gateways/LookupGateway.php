<?php

namespace App\Gateways;

use App\Services\XblService;
use App\Services\SteamService;
use App\Services\MinecraftService;
use http\Exception\InvalidArgumentException;

class LookupGateway
{
    protected array $services = [
        'minecraft' => MinecraftService::class,
        'steam' => SteamService::class,
        'xbl' => XblService::class,
    ];

    public function __invoke(string $type)
    {
        return $this->resolve($type);
    }

    public function resolve(string $type)
    {
        if (! array_key_exists($type, $this->services)) {
            throw new InvalidArgumentException('Invalid Lookup service.');
        }

        return app($this->services[$type]);
    }
}
