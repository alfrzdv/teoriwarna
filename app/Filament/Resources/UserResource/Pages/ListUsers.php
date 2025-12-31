<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

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
            'users' => Tab::make('Users')
                ->modifyQueryUsing(fn ($query) => $query->where('role', 'user'))
                ->badge(fn () => static::getResource()::getModel()::where('role', 'user')->count()),
            'admins' => Tab::make('Admins')
                ->modifyQueryUsing(fn ($query) => $query->where('role', 'admin'))
                ->badge(fn () => static::getResource()::getModel()::where('role', 'admin')->count()),
            'banned' => Tab::make('Banned')
                ->modifyQueryUsing(fn ($query) => $query->where('is_banned', true))
                ->badge(fn () => static::getResource()::getModel()::where('is_banned', true)->count()),
        ];
    }
}
