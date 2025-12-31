<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

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
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
            '5_stars' => Tab::make('5 ⭐')
                ->modifyQueryUsing(fn ($query) => $query->where('rating', 5))
                ->badge(fn () => static::getResource()::getModel()::where('rating', 5)->count()),
        ];
    }
}
