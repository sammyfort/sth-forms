<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Business;
use App\Models\Signboard;
use App\Models\SignboardSubscription;
use App\Models\SignboardSubscriptionPlan;
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

            Stat::make('Businesses', Business::query()->count()),

            Stat::make('Signboards', Signboard::query()->count())->color('blue'),

            Stat::make(
                'Signboard Subscriptions',
                SignboardSubscription::query()
                    ->where('payment_status', PaymentStatus::PAID)
                    ->whereDate('ends_at', '>=', now())
                    ->count()
            ),

            Stat::make('Signboard Subscription Plans', SignboardSubscriptionPlan::query()->count()),
        ];
    }
}
