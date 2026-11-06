<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\CompanyInfos\CompanyInfoResource;
use App\Filament\Resources\CoreValues\CoreValueResource;
use App\Filament\Resources\Equipment\EquipmentResource;
use App\Filament\Resources\EquipmentCategories\EquipmentCategoryResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Offices\OfficeResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\StaffRoles\StaffRoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Who can open which admin pages.
 */
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private const CONTENT_RESOURCES = [
        ProjectResource::class,
        ServiceResource::class,
        ClientResource::class,
        EquipmentResource::class,
        EquipmentCategoryResource::class,
        StaffRoleResource::class,
        CompanyInfoResource::class,
        OfficeResource::class,
        CoreValueResource::class,
        CertificationResource::class,
        InquiryResource::class,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get(ProjectResource::getUrl())->assertRedirect('/admin/login');
    }

    public function test_admin_can_open_every_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin')->assertOk();

        foreach ([...self::CONTENT_RESOURCES, UserResource::class] as $resource) {
            $this->actingAs($admin)->get($resource::getUrl())->assertOk();
        }

        $project = Project::first();
        $this->actingAs($admin)->get(ProjectResource::getUrl('create'))->assertOk();
        $this->actingAs($admin)->get(ProjectResource::getUrl('edit', ['record' => $project]))->assertOk();
    }

    public function test_editor_can_open_content_pages_but_not_users(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        foreach (self::CONTENT_RESOURCES as $resource) {
            $this->actingAs($editor)->get($resource::getUrl())->assertOk();
        }

        $this->actingAs($editor)->get(UserResource::getUrl())->assertForbidden();
    }

    public function test_only_admins_see_the_contract_value(): void
    {
        $project = Project::first();
        $url = ProjectResource::getUrl('edit', ['record' => $project]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get($url)->assertSee('Contract value (private)');

        $this->actingAs(User::factory()->create(['role' => 'editor']))
            ->get($url)->assertDontSee('Contract value (private)');
    }

    public function test_users_without_a_known_role_cannot_enter(): void
    {
        $stranger = User::factory()->create(['role' => 'viewer']);

        $this->actingAs($stranger)->get('/admin')->assertForbidden();
    }
}
