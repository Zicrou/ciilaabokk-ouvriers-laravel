<?php

namespace App\Services;

use App\Models\Ouvrier;
use Kreait\Firebase\Contract\Database;

class FirebaseOuvrierLocationWriter
{
    public function __construct(private Database $database) {}

    public function write(Ouvrier $ouvrier, float $latitude, float $longitude): string
    {
        $updatedAt = now()->toIso8601String();

        $this->database
            ->getReference("ouvriers/{$ouvrier->getKey()}/position")
            ->set([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'updated_at' => $updatedAt,
            ]);

        return $updatedAt;
    }
}
