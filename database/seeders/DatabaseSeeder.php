<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Loads the content from the profile PDF. Safe to run again: every seeder updates
 * existing rows instead of adding duplicates.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            ServiceSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            EquipmentSeeder::class,
            StaffRoleSeeder::class,
            CertificationSeeder::class,
            LocalAdminSeeder::class,
        ]);
    }
}
