<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    //protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
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
                            ->required()
                            ->numeric()
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
                    ->schema([
                        Forms\Components\Repeater::make('product_images')
                            ->label('Gambar')
                            ->relationship('product_images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Upload Gambar')
                                    ->image()
                                    ->disk('public')
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
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Stok')
                    ->schema([
                        Forms\Components\Placeholder::make('current_stock')
                            ->label('Stok Saat Ini')
                            ->content(fn ($record) => $record ? $record->stock . ' unit' : 'Belum ada stok')
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),

                        Forms\Components\TextInput::make('initial_stock')
                            ->label('Stok Awal')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Isi dengan jumlah stok awal produk')
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('stock_adjustment')
                            ->label('Tambah/Kurangi Stok')
                            ->numeric()
                            ->helperText('Gunakan angka negatif untuk mengurangi stok (contoh: -5 untuk mengurangi 5 unit)')
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('stock_note')
                            ->label('Catatan Perubahan Stok')
                            ->rows(2)
                            ->placeholder('Opsional - jelaskan alasan perubahan stok')
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product_images.image_path')
                    ->label('Gambar')
                    ->circular()
                    ->limit(1),

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
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->suffix(' unit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
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
                    ->query(fn ($query) => $query->where('stock', '<=', 10)->where('stock', '>', 0)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('add_stock')
                        ->label('Tambah Stok')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\Placeholder::make('current_stock')
                                ->label('Stok Saat Ini')
                                ->content(fn ($record) => $record->stock . ' unit'),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(1),

                            Forms\Components\Textarea::make('note')
                                ->label('Catatan')
                                ->rows(2)
                                ->placeholder('Opsional'),
                        ])
                        ->action(function (Product $record, array $data) {
                            $record->addStock($data['quantity'], $data['note'] ?? 'Manual stock adjustment');
                        }),

                    Tables\Actions\Action::make('reduce_stock')
                        ->label('Kurangi Stok')
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Placeholder::make('current_stock')
                                ->label('Stok Saat Ini')
                                ->content(fn ($record) => $record->stock . ' unit'),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->maxValue(fn ($record) => $record->stock)
                                ->helperText(fn ($record) => 'Maksimal: ' . $record->stock . ' unit')
                                ->default(1),

                            Forms\Components\Textarea::make('note')
                                ->label('Catatan')
                                ->rows(2)
                                ->placeholder('Opsional'),
                        ])
                        ->action(function (Product $record, array $data) {
                            $record->reduceStock($data['quantity'], $data['note'] ?? 'Manual stock adjustment');
                        }),
                ])
                ->label('Kelola Stok')
                ->icon('heroicon-o-cube')
                ->color('warning')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
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
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

}
