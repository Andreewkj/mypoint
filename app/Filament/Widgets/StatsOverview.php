<?php

namespace App\Filament\Widgets;

use App\Models\PointMarking;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $workedStats = $this->calculateCurrentDayWorkedHours();

        return [
            Stat::make('Problemas?', $workedStats['problems'])
                ->description('Fique atento aqui')
                ->color('warning')
                ->descriptionIcon('heroicon-m-hand-raised'),
            Stat::make('Horas extras acumuladas', $workedStats['extraWorkedHours'])
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total da jornada', $workedStats['totalWorkedHours'],)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->description('Relativo ao dia de hoje')
                ->color('success'),
        ];
    }

    private function calculateCurrentDayWorkedHours()
    {
        $user = auth()->user();

        if ($user->isMaster()) {
            return [
                'totalWorkedHours' => '0:00',
                'extraWorkedHours' => '0:00',
                'problems' => '',
            ];
        }
        $userPointMarkings = PointMarking::query()->where('user_id', $user->id)->where('clocking_at', '>=', now()->startOfDay())->where('clocking_at', '<=', now()->endOfDay())->orderBy('clocking_at')->get();

        $start = Carbon::parse($userPointMarkings->first()->clocking_at);
        $end = Carbon::parse($userPointMarkings->last()->clocking_at);

        $totalWorkedHours = $end->diffInSeconds($start);
        $lunchTime = 3600;
        $cltJourney = 3600 * 8;
        $totalMarkers = $userPointMarkings->count();

        if ($totalMarkers > 4) {
            $totalWorkedHours = $totalWorkedHours - $lunchTime;
        }

        $extraWorkedHours = $totalWorkedHours - $cltJourney;

        if (! $totalMarkers % 2 == 0) {
            $problems = 'Número de marcações errada';
        }

        return [
            'totalWorkedHours' => gmdate('H:i', $totalWorkedHours),
            'extraWorkedHours' => gmdate('H:i', $extraWorkedHours),
            'problems' => $problems,
        ];
    }
}
