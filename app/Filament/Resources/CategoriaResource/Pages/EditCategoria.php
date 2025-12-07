<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;

     protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterSave()
    {
         notification::make()
         ->title('Categoria actualizada')
         ->body('La categoria se actualizo con exito')
         ->success()
         ->send();

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
                    ->title('Categoria Eliminada')
                    ->body('La categoria se elimino exitosamente')
                    ->success()
            ),
        ];
    }



}
