<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Client;
use App\Models\CompanyInfo;
use App\Models\CoreValue;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Office;
use App\Models\Project;
use App\Models\Service;
use App\Models\StaffRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_the_profile_content(): void
    {
        $this->seed();

        $this->assertSame(1, CompanyInfo::count());
        $this->assertSame(2, Office::count());
        $this->assertSame(5, CoreValue::count());
        $this->assertSame(13, Service::count());
        $this->assertSame(11, Client::count());
        $this->assertSame(11, Project::count());
        $this->assertSame(3, EquipmentCategory::count());
        $this->assertSame(67, Equipment::count());
        $this->assertSame(15, StaffRole::count());
        $this->assertSame(2, Certification::count());

        $this->assertSame(3, count(CompanyInfo::current()->missionPoints()));
        $this->assertSame(1, Office::where('is_head_office', true)->count());
    }

    public function test_keeps_private_data_out_of_the_seed(): void
    {
        $this->seed();

        $this->assertSame(0, Project::whereNotNull('contract_value')->count(), 'contract values must not be seeded');
        $this->assertSame(0, Project::where('show_value', true)->count());
        $this->assertSame(0, Certification::whereNotNull('number')->count(), 'certificate numbers must not be seeded');
        $this->assertSame(0, Client::where('show_logo', true)->count(), 'logos stay hidden until permission');
        $this->assertFalse(Certification::where('title', 'Trade licence')->value('is_public'), 'the expired licence stays hidden');
    }

    public function test_links_projects_to_clients_and_services(): void
    {
        $this->seed();

        $tunnel = Project::where('slug', 'karnaphuli-tunnel-sand-dredging-filling')->first();

        $this->assertSame('China Communications Construction Company (CCCC)', $tunnel->client->name);
        $this->assertEqualsCanonicalizing(
            ['river-dredging-and-bank-protection', 'land-reclamation-and-development'],
            $tunnel->services->pluck('slug')->all(),
        );
        $this->assertSame(2, Client::where('name', 'Karnafuly Dry Dock Ltd.')->first()->projects()->count());
    }

    public function test_lists_projects_newest_first_by_end_year(): void
    {
        $this->seed();

        $slugs = Project::latestFirst()->pluck('slug');

        // The 2017–2024 dredger job counts as 2024, so it comes first.
        $this->assertSame('biwta-dredger-hulls-barges-tug-boats', $slugs->first());
    }

    public function test_running_the_seeder_twice_adds_no_duplicates(): void
    {
        $this->seed();
        $this->seed();

        $this->assertSame(11, Project::count());
        $this->assertSame(13, Service::count());
        $this->assertSame(67, Equipment::count());
        $this->assertSame(1, CompanyInfo::count());
    }

    public function test_does_not_create_the_local_admin_outside_local(): void
    {
        $this->seed();

        $this->assertSame(0, User::count());
    }
}
