<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CallsInternalApi;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use CallsInternalApi, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInternalApi();
    }

    public function test_lists_only_published_projects_newest_first(): void
    {
        Project::factory()->create(['title' => 'Old job', 'start_date' => '2018-01-01', 'end_date' => '2018-12-31']);
        Project::factory()->create(['title' => 'Long job', 'start_date' => '2017-01-01', 'end_date' => '2024-06-30']);
        Project::factory()->unpublished()->create(['title' => 'Draft job']);

        $this->api('GET', 'projects')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.*.title', ['Long job', 'Old job'])
            ->assertJsonPath('data.0.years', '2017–2024');
    }

    public function test_filters_by_service_and_client_type(): void
    {
        $dredging = Service::factory()->create(['slug' => 'river-dredging']);
        $un = Client::factory()->create(['type' => 'un_agency']);

        $match = Project::factory()->for($un)->create(['title' => 'UN dredging']);
        $match->services()->attach($dredging);
        // The client type is set explicitly: the factory picks a random one.
        Project::factory()->for(Client::factory()->create(['type' => 'private']))->create(['title' => 'Private dredging'])->services()->attach($dredging);
        Project::factory()->for($un)->create(['title' => 'UN supply']);

        $this->api('GET', 'projects?service=river-dredging&client_type=un_agency')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'UN dredging')
            ->assertJsonPath('data.0.services.0.slug', 'river-dredging');
    }

    public function test_rejects_unknown_filters(): void
    {
        $this->api('GET', 'projects?service=no-such-service&client_type=alien&per_page=500')
            ->assertUnprocessable()
            ->assertJsonStructure(['message' => ['service', 'client_type', 'per_page']]);
    }

    public function test_paginates(): void
    {
        Project::factory()->count(5)->create();

        $this->api('GET', 'projects?per_page=2&page=3')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta', ['current_page' => 3, 'last_page' => 3, 'per_page' => 2, 'total' => 5]);
    }

    public function test_shows_a_project_with_its_gallery_and_scope(): void
    {
        $project = Project::factory()->create([
            'slug'  => 'tunnel-sand-filling',
            'scope' => "Sand dredging\nSand filling\n",
        ]);
        $project->images()->create(['image_url' => 'projects/b.jpg', 'caption' => 'Second', 'sort_order' => 2]);
        $project->images()->create(['image_url' => 'projects/a.jpg', 'caption' => 'First', 'sort_order' => 1]);

        $this->api('GET', 'projects/tunnel-sand-filling')
            ->assertOk()
            ->assertJsonPath('data.scope', ['Sand dredging', 'Sand filling'])
            ->assertJsonPath('data.images.*.caption', ['First', 'Second'])
            ->assertJsonPath('data.images.0.url', url('/storage/projects/a.jpg'))
            ->assertJsonPath('data.client.name', $project->client->name);
    }

    public function test_the_card_uses_the_first_photo_as_cover(): void
    {
        $project = Project::factory()->create();
        $project->images()->create(['image_url' => 'projects/later.jpg', 'sort_order' => 5]);
        $project->images()->create(['image_url' => 'projects/cover.jpg', 'sort_order' => 1]);

        $this->api('GET', 'projects')
            ->assertJsonPath('data.0.cover_image.url', url('/storage/projects/cover.jpg'));
    }

    public function test_unpublished_and_unknown_projects_are_not_found(): void
    {
        Project::factory()->unpublished()->create(['slug' => 'secret-draft']);

        $this->api('GET', 'projects/secret-draft')->assertNotFound()->assertJsonPath('error', true);
        $this->api('GET', 'projects/nothing-here')->assertNotFound();
    }

    public function test_contract_value_is_only_sent_when_switched_on(): void
    {
        Project::factory()->create(['slug' => 'private-value', 'contract_value' => 5000000, 'show_value' => false]);
        Project::factory()->create(['slug' => 'public-value', 'contract_value' => 750000, 'show_value' => true]);

        $this->api('GET', 'projects/private-value')->assertOk()->assertJsonMissingPath('data.contract_value');
        $this->api('GET', 'projects/public-value')->assertOk()->assertJsonPath('data.contract_value', '750000.00');
        $this->assertStringNotContainsString('5000000', $this->api('GET', 'projects')->getContent());
    }
}
