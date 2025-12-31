<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Coupon')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kupon')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->uppercase()
                            ->helperText('Kode unik untuk kupon (akan otomatis uppercase)'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Diskon')
                            ->options([
                                'percentage' => 'Persentase',
                                'fixed' => 'Nominal Tetap',
                            ])
                            ->required()
                            ->reactive()
                            ->default('percentage'),

                        Forms\Components\TextInput::make('value')
                            ->label('Nilai Diskon')
                            ->numeric()
                            ->required()
                            ->suffix(fn (Forms\Get $get) => $get('type') === 'percentage' ? '%' : 'Rp')
                            ->helperText(fn (Forms\Get $get) => $get('type') === 'percentage' ? 'Masukkan nilai 1-100' : 'Masukkan nominal dalam Rupiah'),

                        Forms\Components\TextInput::make('min_purchase')
                            ->label('Minimum Pembelian')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Minimum pembelian untuk menggunakan kupon'),

                        Forms\Components\TextInput::make('max_discount')
                            ->label('Maksimal Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'percentage')
                            ->helperText('Batas maksimal diskon untuk tipe persentase'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Batasan Penggunaan')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Batas Penggunaan Total')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Batas total penggunaan kupon (0 = unlimited)'),

                        Forms\Components\TextInput::make('usage_limit_per_user')
                            ->label('Batas Per User')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Batas penggunaan per user (0 = unlimited)'),

                        Forms\Components\DateTimePicker::make('valid_from')
                            ->label('Berlaku Dari')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('valid_until')
                            ->label('Berlaku Sampai')
                            ->after('valid_from'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Diskon')
                    ->getStateUsing(fn ($record) => $record->type === 'percentage'
                        ? $record->value . '%'
                        : 'Rp ' . number_format($record->value, 0, ',', '.'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('min_purchase')
                    ->label('Min. Pembelian')
                    ->money('IDR')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('usage')
                    ->label('Penggunaan')
                    ->getStateUsing(fn ($record) => $record->coupon_usages()->count() . ' / ' . ($record->usage_limit ?: '∞'))
                    ->badge(),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),

                Tables\Columns\TextColumn::make('valid_from')
                    ->label('Berlaku Dari')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed' => 'Nominal Tetap',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon('heroicon-o-arrow-path')
                    ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                    ->action(fn ($record) => $record->update(['is_active' => !$record->is_active]))
                    ->successNotificationTitle('Status berhasil diubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => true])))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => false])))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view' => Pages\ViewCoupon::route('/{record}'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
