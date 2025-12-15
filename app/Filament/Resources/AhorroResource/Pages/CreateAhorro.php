<?php

namespace App\Filament\Resources\AhorroResource\Pages;

use App\Filament\Resources\AhorroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAhorro extends CreateRecord
{
    protected static string $resource = AhorroResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate()
    {
         Notification::make()
         ->title('Ahorro creado')
         ->body('El ahorro se creo con exito')
         ->success()
         ->send();

    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
               ->label('Registrar ahorro'),

            $this->getCancelFormAction()
                ->label('Cancelar')

        ];
    }
}
