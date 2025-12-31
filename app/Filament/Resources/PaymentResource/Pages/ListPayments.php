<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'pending')->count()),
            'success' => Tab::make('Success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'success'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'success')->count()),
            'failed' => Tab::make('Failed')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'failed'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'failed')->count()),
        ];
    }
}
