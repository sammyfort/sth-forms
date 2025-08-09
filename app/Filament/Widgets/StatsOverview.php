<?php

namespace App\Filament\Widgets;

use App\Enums\JobStatus;
use App\Enums\PaymentStatus;
use App\Models\Business;
use App\Models\Job;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Service;
use App\Models\Signboard;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Analytics';

    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Service Providers', Service::query()->count()),

            Stat::make('Businesses', Business::query()->count()),

            Stat::make('Signboards', Signboard::query()->count())->color('blue'),

            Stat::make('Products', Signboard::query()->count())->descriptionColor('green'),

            Stat::make(
                'Active Jobs',
                Job::query()
                    ->where('status', JobStatus::ACTIVE)
                    ->count()
            )->color('blue'),

            Stat::make(
                'Active Promotions',
                Promotion::query()
                    ->where('payment_status', PaymentStatus::PAID)
                    ->whereTodayOrAfter('ends_at')
                    ->count()
            ),

            Stat::make('Promotion Plans', PromotionPlan::query()->count()),
        ];
    }
}
