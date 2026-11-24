<?php

namespace App\Services;

use App\Models\Ouvrier;
use Kreait\Firebase\Contract\Database;

class FirebaseOuvrierLocationWriter
{
    private const HISTORY_RETENTION_MILLISECONDS = 24 * 60 * 60 * 1000;

    public function __construct(private Database $database) {}

    public function write(Ouvrier $ouvrier, float $latitude, float $longitude): string
    {
        $recordedAt = now()->utc();
        $recordedAtMs = $recordedAt->valueOf();
        $recordedAtIso = $recordedAt->toIso8601String();
        $point = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'recorded_at' => $recordedAtIso,
            'recorded_at_ms' => $recordedAtMs,
        ];
        $ouvrierReference = $this->database->getReference("ouvriers/{$ouvrier->getKey()}");
        $historyReference = $ouvrierReference->getChild('positions');

        $historyReference->push($point);
        $ouvrierReference->getChild('position')->set([
            ...$point,
            'updated_at' => $recordedAtIso,
            'updated_at_ms' => $recordedAtMs,
        ]);
        $this->removeExpiredPoints($historyReference, $recordedAtMs);

        return $recordedAtIso;
    }

    public function read(Ouvrier $ouvrier, ?int $sinceMs = null): array
    {
        $cutoffMs = now()->utc()->valueOf() - self::HISTORY_RETENTION_MILLISECONDS;
        $ouvrierReference = $this->database->getReference("ouvriers/{$ouvrier->getKey()}");
        $historyReference = $ouvrierReference->getChild('positions');

        $this->removeExpiredPoints($historyReference, $cutoffMs);

        $query = $historyReference->orderByChild('recorded_at_ms');
        $query = $sinceMs === null
            ? $query->startAt($cutoffMs)
            : $query->startAfter(max($sinceMs, $cutoffMs));
        $records = $query->getValue();

        $positions = [];
        if (is_array($records)) {
            foreach ($records as $key => $record) {
                if (! is_array($record)) {
                    continue;
                }

                $record['id'] = (string) $key;
                $positions[] = $record;
            }
        }

        usort(
            $positions,
            fn (array $first, array $second) => $first['recorded_at_ms'] <=> $second['recorded_at_ms'],
        );

        $position = $ouvrierReference->getChild('position')->getValue();
        if (
            ! is_array($position)
            || ! isset($position['updated_at_ms'])
            || ! is_numeric($position['updated_at_ms'])
            || (int) $position['updated_at_ms'] < $cutoffMs
        ) {
            $position = null;
        }

        return [
            'position' => $position,
            'positions' => $positions,
        ];
    }

    private function removeExpiredPoints($historyReference, int $cutoffMs): void
    {
        $expiredRecords = $historyReference
            ->orderByChild('recorded_at_ms')
            ->endAt($cutoffMs)
            ->getValue();

        if (! is_array($expiredRecords)) {
            return;
        }

        foreach (array_keys($expiredRecords) as $key) {
            $historyReference->getChild((string) $key)->remove();
        }
    }
}
