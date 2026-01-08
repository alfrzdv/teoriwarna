<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set initial stock from the form
        if (isset($data['initial_stock']) && $data['initial_stock'] > 0) {
            $data['stock'] = $data['initial_stock'];
        } else {
            $data['stock'] = 0;
        }

        // Remove the temporary field
        unset($data['initial_stock']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Stock is already set in mutateFormDataBeforeCreate
        // No additional stock movement tracking needed
    }

    protected function getCreatedNotification(): ?Notification
    {
        $stockInfo = $this->record->stock > 0
            ? " dengan stok awal {$this->record->stock} unit"
            : '';

        return Notification::make()
            ->success()
            ->title('Produk berhasil dibuat')
            ->body("Produk telah ditambahkan ke katalog{$stockInfo}.")
            ->duration(3000);
    }
}
