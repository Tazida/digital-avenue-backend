<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CallsInternalApi;
use Tests\TestCase;

class ServiceApiTest extends TestCase
{
    use CallsInternalApi, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInternalApi();
    }

    public function test_lists_published_services_grouped_by_category(): void
    {
        Service::factory()->create(['title' => 'Dredging', 'category' => 'dredging_land']);
        Service::factory()->create(['title' => 'Roads', 'category' => 'civil_infrastructure']);
        Service::factory()->unpublished()->create(['title' => 'Hidden', 'category' => 'machinery_supply']);

        $this->api('GET', 'services')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.key', 'dredging_land')
            ->assertJsonPath('data.0.label', 'Dredging and land development')
            ->assertJsonPath('data.0.services.0.title', 'Dredging')
            ->assertJsonPath('data.1.key', 'civil_infrastructure');
    }

    public function test_counts_only_published_projects(): void
    {
        $service = Service::factory()->create();
        $service->projects()->attach(Project::factory()->create());
        $service->projects()->attach(Project::factory()->unpublished()->create());

        $this->api('GET', 'services')->assertJsonPath('data.0.services.0.projects_count', 1);
    }

    public function test_shows_a_service_with_paragraphs_and_its_published_projects(): void
    {
        $service = Service::factory()->create([
            'slug'        => 'river-dredging',
            'description' => "First paragraph\nstill first.\n\nSecond paragraph.",
        ]);
        $service->projects()->attach(Project::factory()->create(['title' => 'Shown']));
        $service->projects()->attach(Project::factory()->unpublished()->create(['title' => 'Draft']));

        $this->api('GET', 'services/river-dredging')
            ->assertOk()
            ->assertJsonPath('data.description', ['First paragraph still first.', 'Second paragraph.'])
            ->assertJsonPath('data.projects.*.title', ['Shown']);
    }

    public function test_unpublished_services_are_not_found(): void
    {
        Service::factory()->unpublished()->create(['slug' => 'draft-service']);

        $this->api('GET', 'services/draft-service')->assertNotFound();
    }
}
