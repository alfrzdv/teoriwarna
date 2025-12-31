<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $navigationGroup = 'Toko';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->description('Detail produk yang akan dijual')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->inputMode('decimal'),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi'),
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('active'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Gambar Produk')
                    ->description('Upload gambar produk (max 5 gambar)')
                    ->schema([
                        Forms\Components\Repeater::make('product_images')
                            ->label('Gambar Produk')
                            ->relationship('product_images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Gambar')
                                    ->image()
                                    ->directory('product-images')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->required(),
                                Forms\Components\Toggle::make('is_primary')
                                    ->label('Gambar Utama')
                                    ->default(false),
                            ])
                            ->maxItems(5)
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Stok Produk')
                    ->description('Informasi stok produk')
                    ->schema([
                        Forms\Components\Placeholder::make('current_stock')
                            ->label('Stok Saat Ini')
                            ->content(fn ($record) => $record ? $record->getCurrentStock() . ' unit' : 'Belum ada stok'),

                        Forms\Components\TextInput::make('initial_stock')
                            ->label('Stok Awal')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Hanya untuk produk baru')
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrated(false),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product_images.image_path')
                    ->label('Gambar')
                    ->circular()
                    ->limit(1)
                    ->defaultImageUrl('https://placehold.co/200x200?text=No+Image'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->getStateUsing(fn ($record) => $record->getCurrentStock())
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->suffix(' unit')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stok Rendah')
                    ->query(fn ($query) => $query->whereHas('product_stocks', function ($q) {
                        $q->selectRaw('product_id')
                          ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock')
                          ->groupBy('product_id')
                          ->having('stock', '<=', 10);
                    })),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('manage_stock')
                    ->label('Kelola Stok')
                    ->icon('heroicon-o-cube')
                    ->color('warning')
                    ->form([
                        Forms\Components\Placeholder::make('current_stock')
                            ->label('Stok Saat Ini')
                            ->content(fn ($record) => $record->getCurrentStock() . ' unit'),

                        Forms\Components\Radio::make('action')
                            ->label('Aksi')
                            ->options([
                                'add' => 'Tambah Stok',
                                'reduce' => 'Kurangi Stok',
                            ])
                            ->required()
                            ->default('add')
                            ->inline(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->rows(2),
                    ])
                    ->action(function (Product $record, array $data) {
                        if ($data['action'] === 'add') {
                            $record->addStock($data['quantity'], $data['note'] ?? 'Manual stock adjustment');
                        } else {
                            $record->reduceStock($data['quantity'], $data['note'] ?? 'Manual stock adjustment');
                        }
                    })
                    ->successNotificationTitle('Stok berhasil diupdate'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->activate())
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->deactivate())
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $lowStockCount = static::getModel()::whereHas('product_stocks', function ($q) {
            $q->selectRaw('product_id')
              ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock')
              ->groupBy('product_id')
              ->having('stock', '<=', 10);
        })->count();

        return $lowStockCount > 0 ? (string) $lowStockCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
