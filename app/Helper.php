<?php

namespace App;

use App\Models\OtherData;
use Illuminate\Console\Scheduling\Schedule;

class Helper
{
    public static function addHistory(string $route, string $name): void
    {
        $history = session('history');

        if (count($history) == 0) {
            Helper::deleteArray($history, $route, $name);
        } else {
            if ($history[count($history) - 1]['name'] !== $name) {
                Helper::deleteArray($history, $route, $name);
            }
        }
    }

    public static function deleteArray($history, $route, $name)
    {
        if (count($history) >= 5) {
            for ($i = 0; $i < count($history); $i++) {
                if ($i == count($history) - 1) {
                    unset($history[$i]);
                } else {
                    $history[$i] = $history[$i + 1];
                }
            }
        }

        $history[count($history)] = [
            'route' => $route,
            'name' => $name,
        ];

        session()->put('history', $history);
    }

    public static function getHariMasukForScheduling(): array
    {
        $days = OtherData::where('name', 'Hari Masuk')->first();
        $days = explode(', ', $days->value);
        $result = [];
        foreach ($days as $day) {
            if ($day == 'Senin') {
                $result[] = Schedule::MONDAY;
            } elseif ($day == 'Selasa') {
                $result[] = Schedule::TUESDAY;
            } elseif ($day == 'Rabu') {
                $result[] = Schedule::WEDNESDAY;
            } elseif ($day == 'Kamis') {
                $result[] = Schedule::THURSDAY;
            } elseif ($day == 'Jumat') {
                $result[] = Schedule::FRIDAY;
            } elseif ($day == 'Sabtu') {
                $result[] = Schedule::SATURDAY;
            } elseif ($day == 'Minggu') {
                $result[] = Schedule::SUNDAY;
            }
        }

        return $result;
    }
}
