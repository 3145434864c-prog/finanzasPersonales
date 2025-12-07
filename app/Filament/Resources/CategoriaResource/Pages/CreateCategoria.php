<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate()
    {
         notification::make()
         ->title('Categoria creada')
         ->body('La categoria se creo con exito')
         ->success()
         ->send();

    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
               ->label('Registrar categoria'),

            $this->getCancelFormAction()
                ->label('Cancelar')
                
        ];
    }

}
