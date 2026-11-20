<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Support\Str;

trait HasUuid
{
    use HasUniqueStringIds;

    public function newUniqueId(): string
    {
        return (string) Str::uuid();
    }

    protected function isValidUniqueId($value): bool
    {
        return Str::isUuid($value);
    }
}