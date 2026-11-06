<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * Dashboard: the five newest unread inquiries.
 */
class LatestInquiries extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'New inquiries';

    public function table(Table $table): Table
    {
        return $table
            ->query(Inquiry::query()->where('status', 'new')->with('service')->latest()->limit(5))
            ->paginated(false)
            ->emptyStateHeading('No new inquiries')
            ->columns([
                TextColumn::make('created_at')->label('Received')->since(),
                TextColumn::make('name')->description(fn (Inquiry $record): string => $record->email),
                TextColumn::make('company')->placeholder('—'),
                TextColumn::make('service.title')->label('Service')->placeholder('—'),
                TextColumn::make('message')->limit(60),
            ])
            ->headerActions([
                Action::make('inbox')
                    ->label('Open inbox')
                    ->url(InquiryResource::getUrl()),
            ]);
    }
}
