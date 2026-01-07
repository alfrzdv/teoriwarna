<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle stock adjustment from form
        if (isset($data['stock_adjustment']) && $data['stock_adjustment'] != 0) {
            $adjustment = (int) $data['stock_adjustment'];
            $note = $data['stock_note'] ?? 'Stock adjustment from edit form';

            if ($adjustment > 0) {
                $this->record->addStock($adjustment, $note);
            } else {
                $this->record->reduceStock(abs($adjustment), $note);
            }
        }

        // Remove temporary fields
        unset($data['stock_adjustment'], $data['stock_note']);

        return $data;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Produk berhasil diperbarui')
            ->body('Perubahan telah disimpan.')
            ->duration(3000);
    }
}
