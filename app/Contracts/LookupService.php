<?php

namespace App\Contracts;

interface LookupService
{
    public static function type(): string;

    public function lookup(?string $username, ?string $id);
}
