<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'pending')->count()),
            'in_progress' => Tab::make('In Progress')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'in_progress'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'in_progress')->count()),
            'resolved' => Tab::make('Resolved')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'resolved')),
        ];
    }
}
