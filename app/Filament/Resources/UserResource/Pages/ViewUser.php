<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi User')
                    ->schema([
                        Infolists\Components\ImageEntry::make('profile_picture')
                            ->label('Foto Profil')
                            ->circular()
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telepon'),
                        Infolists\Components\TextEntry::make('role')
                            ->label('Role')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'primary',
                                'user' => 'secondary',
                                default => 'gray',
                            }),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean(),
                        Infolists\Components\IconEntry::make('is_banned')
                            ->label('Banned')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('last_login')
                            ->label('Login Terakhir')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Terdaftar Sejak')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Statistik')
                    ->schema([
                        Infolists\Components\TextEntry::make('orders_count')
                            ->label('Total Pesanan')
                            ->state(fn ($record) => $record->orders()->count()),
                        Infolists\Components\TextEntry::make('total_spent')
                            ->label('Total Belanja')
                            ->state(fn ($record) => $record->orders()->where('status', 'completed')->sum('total_amount'))
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('addresses_count')
                            ->label('Alamat Tersimpan')
                            ->state(fn ($record) => $record->user_addresses()->count()),
                    ])
                    ->columns(3),
            ]);
    }
}
