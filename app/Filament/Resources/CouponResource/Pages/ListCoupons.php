<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', true))
                ->badge(fn () => static::getResource()::getModel()::where('is_active', true)->count()),
            'inactive' => Tab::make('Inactive')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', false))
                ->badge(fn () => static::getResource()::getModel()::where('is_active', false)->count()),
        ];
    }
}
