<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\Estudiante;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Convenios', Convenio::count())
            ->chart([2, 7, 10])
            ->color('success'),
            Stat::make('Total de Empresas', Empresa::count())
            ->chart([1, 2, 5, 6, 7, 8, 17])
            ->color('info'),
            Stat::make('Total de Estudiantes', Estudiante::count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('danger'),
        ];
    }
}
