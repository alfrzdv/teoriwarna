<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'pending')->count()),
            'paid' => Tab::make('Paid')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'paid'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'paid')->count()),
            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'processing'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'processing')->count()),
            'shipped' => Tab::make('Shipped')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'shipped'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'shipped')->count()),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed')),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}
