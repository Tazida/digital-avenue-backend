<?php

namespace App\Filament\Resources\CompanyInfos\Pages;

use App\Filament\Resources\CompanyInfos\CompanyInfoResource;
use App\Models\CompanyInfo;
use Filament\Resources\Pages\EditRecord;

/**
 * Opens the single company-info row (creating it on first use), so the URL needs no id.
 */
class EditCompanyInfo extends EditRecord
{
    protected static string $resource = CompanyInfoResource::class;

    public function mount(int|string|null $record = null): void
    {
        $info = CompanyInfo::current() ?? CompanyInfo::create(['name' => 'Digital Avenue']);

        parent::mount($info->getKey());
    }

    public function getTitle(): string
    {
        return 'Company info';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
