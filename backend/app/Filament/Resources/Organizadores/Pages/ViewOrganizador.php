<?php

namespace App\Filament\Resources\Organizadores\Pages;

use App\Filament\Resources\Organizadores\OrganizadorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganizador extends ViewRecord
{
    protected static string $resource = OrganizadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
