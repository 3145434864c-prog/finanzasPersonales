<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePresupuesto extends CreateRecord
{
    protected static string $resource = PresupuestoResource::class;
    
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
         ->title('Presupuesto creado')
         ->body('El presupuesto se creo con exito')
         ->success()
         ->send();

    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
               ->label('Registrar presupuesto'),

            $this->getCancelFormAction()
                ->label('Cancelar')
                
        ];
    }
}
