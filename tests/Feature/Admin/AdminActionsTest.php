<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\CompanyInfos\Pages\EditCompanyInfo;
use App\Filament\Resources\Inquiries\Pages\ManageInquiries;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\RelationManagers\ImagesRelationManager;
use App\Models\Client;
use App\Models\CompanyInfo;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * What the admin forms and actions actually save.
 */
class AdminActionsTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user);

        return $user;
    }

    public function test_admin_creates_a_project_with_services(): void
    {
        $this->actingAsRole('admin');
        $client = Client::factory()->create();
        $service = Service::factory()->create();

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title'        => 'River bank protection, Sandwip',
                'slug'         => 'river-bank-protection-sandwip',
                'client_id'    => $client->id,
                'services'     => [$service->id],
                'status'       => 'ongoing',
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::where('slug', 'river-bank-protection-sandwip')->firstOrFail();
        $this->assertSame('ongoing', $project->status);
        $this->assertTrue($project->is_published);
        $this->assertTrue($project->services->contains($service));
    }

    public function test_an_editor_saving_a_project_keeps_the_private_contract_value(): void
    {
        $project = Project::factory()->create(['contract_value' => 1500000, 'show_value' => false]);
        $this->actingAsRole('editor');

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->fillForm(['title' => 'New title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $project->refresh();
        $this->assertSame('New title', $project->title);
        $this->assertSame('1500000.00', $project->contract_value);
    }

    public function test_photos_upload_to_the_public_disk_and_go_to_the_end_of_the_gallery(): void
    {
        Storage::fake('public');
        $this->actingAsRole('editor');
        $project = Project::factory()->create();
        $project->images()->create(['image_url' => 'projects/existing.jpg']);

        Livewire::test(ImagesRelationManager::class, ['ownerRecord' => $project, 'pageClass' => EditProject::class])
            ->callTableAction('create', data: [
                'image_url' => UploadedFile::fake()->image('dredger.jpg', 1200, 800),
                'caption'   => 'Cutter suction dredger at work',
            ])
            ->assertHasNoTableActionErrors();

        $photo = $project->images()->where('caption', 'Cutter suction dredger at work')->firstOrFail();
        Storage::disk('public')->assertExists($photo->image_url);
        $this->assertStringStartsWith('projects/', $photo->image_url);
        $this->assertGreaterThan($project->images()->where('image_url', 'projects/existing.jpg')->value('sort_order'), $photo->sort_order);
    }

    public function test_slugs_must_be_unique(): void
    {
        $this->actingAsRole('admin');
        $existing = Project::factory()->create();

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title'     => 'Copy',
                'slug'      => $existing->slug,
                'client_id' => $existing->client_id,
                'status'    => 'completed',
            ])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    public function test_opening_an_inquiry_marks_it_as_read(): void
    {
        $this->actingAsRole('editor');
        $inquiry = Inquiry::factory()->create(['status' => 'new']);

        Livewire::test(ManageInquiries::class)
            ->assertCanSeeTableRecords([$inquiry])
            ->mountTableAction('view', $inquiry);

        $this->assertSame('read', $inquiry->fresh()->status);
    }

    public function test_company_info_page_edits_the_single_row(): void
    {
        $this->actingAsRole('editor');
        CompanyInfo::factory()->create(['name' => 'Digital Avenue']);

        Livewire::test(EditCompanyInfo::class)
            ->fillForm(['tagline' => 'Dredging, land development and civil works across Bangladesh'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(1, CompanyInfo::count());
        $this->assertSame('Dredging, land development and civil works across Bangladesh', CompanyInfo::current()->tagline);
    }

    public function test_admins_cannot_delete_their_own_account(): void
    {
        $admin = $this->actingAsRole('admin');
        $other = User::factory()->create(['role' => 'editor']);

        $this->assertFalse($admin->can('delete', $admin));
        $this->assertTrue($admin->can('delete', $other));
        $this->assertFalse($other->can('viewAny', User::class));
    }
}
