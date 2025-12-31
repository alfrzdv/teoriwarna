<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewCoupon extends ViewRecord
{
    protected static string $resource = CouponResource::class;

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
                Infolists\Components\Section::make('Informasi Coupon')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Kode')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi'),
                        Infolists\Components\TextEntry::make('type')
                            ->label('Tipe')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'percentage' => 'Persentase',
                                'fixed' => 'Nominal Tetap',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('value')
                            ->label('Nilai Diskon')
                            ->formatStateUsing(fn ($record) => $record->type === 'percentage'
                                ? $record->value . '%'
                                : 'Rp ' . number_format($record->value, 0, ',', '.')),
                        Infolists\Components\TextEntry::make('min_purchase')
                            ->label('Minimum Pembelian')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('max_discount')
                            ->label('Maksimal Diskon')
                            ->money('IDR')
                            ->visible(fn ($record) => $record->max_discount),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Batasan & Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('usage_limit')
                            ->label('Batas Penggunaan Total')
                            ->formatStateUsing(fn ($state) => $state ?: 'Unlimited'),
                        Infolists\Components\TextEntry::make('usage_limit_per_user')
                            ->label('Batas Per User')
                            ->formatStateUsing(fn ($state) => $state ?: 'Unlimited'),
                        Infolists\Components\TextEntry::make('current_usage')
                            ->label('Saat Ini Digunakan')
                            ->state(fn ($record) => $record->coupon_usages()->count() . ' kali'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('valid_from')
                            ->label('Berlaku Dari')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('valid_until')
                            ->label('Berlaku Sampai')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
