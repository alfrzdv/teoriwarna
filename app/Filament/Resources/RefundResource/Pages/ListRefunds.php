<?php

namespace App\Filament\Resources\RefundResource\Pages;

use App\Filament\Resources\RefundResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListRefunds extends ListRecords
{
    protected static string $resource = RefundResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'pending')->count()),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'approved')->count()),
            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'processing'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'processing')->count()),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed')),
        ];
    }
}
