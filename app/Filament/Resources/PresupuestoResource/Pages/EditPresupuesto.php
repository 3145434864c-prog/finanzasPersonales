<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPresupuesto extends EditRecord
{
    protected static string $resource = PresupuestoResource::class;

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
         ->title('Presupuesto actualizado')
         ->body('El presupuesto se actualizó con éxito')
         ->success()
         ->send();

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
                    ->title('Presupuesto Eliminado')
                    ->body('El presupuesto se eliminó exitosamente')
                    ->success()
            ),
        ];
    }
}
