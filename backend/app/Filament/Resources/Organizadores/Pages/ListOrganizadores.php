<?php

namespace App\Filament\Resources\Organizadores\Pages;

use App\Filament\Resources\Organizadores\OrganizadorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizadores extends ListRecords
{
    protected static string $resource = OrganizadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
