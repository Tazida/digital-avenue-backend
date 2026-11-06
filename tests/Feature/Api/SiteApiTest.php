<?php

namespace Tests\Feature\Api;

use App\Models\Certification;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CallsInternalApi;
use Tests\TestCase;

/**
 * Home, Company and Capabilities, checked against the real seed content.
 */
class SiteApiTest extends TestCase
{
    use CallsInternalApi, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInternalApi();
        $this->seed();
    }

    public function test_home_returns_key_numbers_and_featured_projects(): void
    {
        $response = $this->api('GET', 'home')
            ->assertOk()
            ->assertJsonPath('data.stats', [
                'projects'        => 11,
                'clients'         => 11,
                'staff_own'       => 65,
                'staff_hire'      => 300,
                'equipment_types' => 67,
            ])
            ->assertJsonCount(4, 'data.featured')
            ->assertJsonCount(13, 'data.services')
            ->assertJsonCount(11, 'data.clients');

        // Featured cards are newest first: the 2017–2024 dredger job leads.
        $response->assertJsonPath('data.featured.0.slug', 'biwta-dredger-hulls-barges-tug-boats');
    }

    public function test_home_leaves_out_unpublished_featured_projects(): void
    {
        Project::where('is_featured', true)->first()->update(['is_published' => false]);

        $this->api('GET', 'home')->assertJsonCount(3, 'data.featured')->assertJsonPath('data.stats.projects', 10);
    }

    public function test_company_returns_the_about_and_contact_content(): void
    {
        $this->api('GET', 'company')
            ->assertOk()
            ->assertJsonPath('data.company.name', 'Digital Avenue')
            ->assertJsonPath('data.company.email', 'digitalavenuebd@gmail.com')
            ->assertJsonCount(5, 'data.company.intro')
            ->assertJsonCount(3, 'data.company.mission')
            ->assertJsonCount(2, 'data.company.founder_message')
            ->assertJsonPath('data.offices.0.label', 'Chattogram')
            ->assertJsonPath('data.offices.0.is_head_office', true)
            ->assertJsonCount(5, 'data.core_values');
    }

    public function test_company_sends_only_public_certifications_without_numbers(): void
    {
        Certification::query()->update(['number' => 'SECRET-123', 'file_url' => 'certifications/scan.pdf']);

        $response = $this->api('GET', 'company')
            ->assertOk()
            ->assertJsonCount(1, 'data.certifications')
            ->assertJsonPath('data.certifications.0.issuer', 'Directorate General of Defence Purchase, Ministry of Defence');

        $this->assertStringNotContainsString('SECRET-123', $response->getContent());
        $this->assertStringNotContainsString('scan.pdf', $response->getContent());
        $this->assertStringNotContainsString('Trade licence', $response->getContent());
    }

    public function test_capabilities_returns_equipment_lists_and_workforce(): void
    {
        $this->api('GET', 'capabilities')
            ->assertOk()
            ->assertJsonCount(3, 'data.equipment')
            ->assertJsonPath('data.equipment.0.slug', 'general')
            ->assertJsonCount(41, 'data.equipment.0.items')
            ->assertJsonPath('data.equipment.0.items.0.name', 'Concrete Mixer Machine with Hopper')
            ->assertJsonCount(15, 'data.workforce')
            ->assertJsonPath('data.totals', ['staff_own' => 65, 'staff_hire' => 300]);
    }

    public function test_every_endpoint_needs_the_internal_token(): void
    {
        foreach (['home', 'company', 'services', 'projects', 'capabilities', 'clients'] as $path) {
            $this->getJson('/api/v1.0/'.$path)->assertUnauthorized();
        }

        $this->postJson('/api/v1.0/inquiries', [])->assertUnauthorized();
    }
}
