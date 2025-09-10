<?php

namespace App\Contracts;

interface LookupService
{
    public static function service(): string;

    public function lookup(?string $username, ?string $id);
}
