<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Certification;
use App\Models\Inquiry;
use App\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard: what needs attention today.
 */
class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newInquiries = Inquiry::where('status', 'new')->count();
        $published = Project::published()->count();
        $total = Project::count();
        $withoutPhotos = Project::published()->doesntHave('images')->count();
        $expiredOrSoon = Certification::whereNotNull('expires_on')->whereDate('expires_on', '<', now()->addDays(60))->count();

        return [
            Stat::make('New inquiries', $newInquiries)
                ->description($newInquiries ? 'Waiting for a reply' : 'All caught up')
                ->icon(Heroicon::OutlinedInboxArrowDown)
                ->color($newInquiries ? 'warning' : 'success')
                ->url(InquiryResource::getUrl()),
            Stat::make('Published projects', "{$published} of {$total}")
                ->icon(Heroicon::OutlinedBriefcase)
                ->url(ProjectResource::getUrl()),
            Stat::make('Published projects without photos', $withoutPhotos)
                ->description($withoutPhotos ? 'Add photos to build trust' : 'Every project has photos')
                ->icon(Heroicon::OutlinedPhoto)
                ->color($withoutPhotos ? 'warning' : 'success')
                ->url(ProjectResource::getUrl()),
            Stat::make('Certificates expired or expiring', $expiredOrSoon)
                ->description('Within 60 days')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->color($expiredOrSoon ? 'danger' : 'success')
                ->url(CertificationResource::getUrl()),
        ];
    }
}
