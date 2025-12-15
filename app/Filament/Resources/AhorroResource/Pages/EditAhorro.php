<?php

namespace App\Filament\Resources\AhorroResource\Pages;

use App\Filament\Resources\AhorroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAhorro extends EditRecord
{
    protected static string $resource = AhorroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterSave()
    {
        Notification::make()
            ->title('Ahorro actualizado')
            ->body('El ahorro se actualizó con éxito')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
