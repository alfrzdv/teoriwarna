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
        // Create initial stock movement record if stock > 0
        if ($this->record->stock > 0) {
            $this->record->addStock(
                $this->record->stock,
                'Initial stock when product created'
            );

            // Reset the stock movement to avoid duplicate
            $this->record->stock_movements()
                ->where('type', 'in')
                ->where('quantity', $this->record->stock)
                ->where('note', 'Initial stock when product created')
                ->delete();
        }
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
