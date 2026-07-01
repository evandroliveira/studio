<?php

namespace App\Support;

use Carbon\Carbon;

class AgendamentoHorario
{
    public static function normalizeClockTime(?string $value, string $fallback = '00:00:00'): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $fallback;
        }

        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value . ':00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        return $fallback;
    }

    public static function durationToMinutes(?string $value, int $fallbackMinutes = 30): int
    {
        $normalized = self::normalizeClockTime($value, sprintf('00:%02d:00', $fallbackMinutes));

        [$hours, $minutes] = array_map('intval', explode(':', $normalized));
        $totalMinutes = ($hours * 60) + $minutes;

        return $totalMinutes > 0 ? $totalMinutes : $fallbackMinutes;
    }

    public static function appointmentOverlaps(
        string $dateA,
        string $startTimeA,
        int $durationMinutesA,
        string $dateB,
        string $startTimeB,
        int $durationMinutesB
    ): bool {
        $startA = Carbon::parse($dateA . ' ' . self::normalizeClockTime($startTimeA));
        $endA = $startA->copy()->addMinutes(max(1, $durationMinutesA));

        $startB = Carbon::parse($dateB . ' ' . self::normalizeClockTime($startTimeB));
        $endB = $startB->copy()->addMinutes(max(1, $durationMinutesB));

        return $startA->lt($endB) && $endA->gt($startB);
    }
}