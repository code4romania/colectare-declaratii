<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\County;
use App\Models\Declaration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $countiesStats = collect();
        County::withCount(['declarations'])->get()
            ->filter(function ($county) {
                return $county->declarations_count > 0;
            })
            ->each(function ($county) use (&$countiesStats) {
                $countiesStats->push(
                    Stat::make($county->name, $county->declarations_count)
                        ->color('primary')
                        ->icon('heroicon-o-document-text')
                );
            });

        return [
            Stat::make(__('app.declaration.plural'), Declaration::count()),
            ...$countiesStats->toArray(),
        ];
    }
}
