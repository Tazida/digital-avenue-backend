<?php

namespace App\Filament\Resources\StaffRoles\Pages;

use App\Filament\Resources\StaffRoles\StaffRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStaffRoles extends ManageRecords
{
    protected static string $resource = StaffRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
