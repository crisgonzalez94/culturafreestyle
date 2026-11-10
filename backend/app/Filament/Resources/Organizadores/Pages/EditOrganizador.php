<?php

namespace App\Filament\Resources\Organizadores\Pages;

use App\Filament\Resources\Organizadores\OrganizadorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizador extends EditRecord
{
    protected static string $resource = OrganizadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
