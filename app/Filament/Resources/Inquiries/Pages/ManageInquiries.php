<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInquiries extends ManageRecords
{
    protected static string $resource = InquiryResource::class;

    // Inquiries come from the website form only: no "New" button.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
