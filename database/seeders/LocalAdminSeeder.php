<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * A test admin for local development only. Never runs outside APP_ENV=local.
 * Real admins are created with: php artisan make:filament-user
 */
class LocalAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        User::updateOrCreate(['email' => 'admin@digitalavenue.test'], [
            'name'     => 'Local Admin',
            'password' => 'password',
            'role'     => 'admin',
        ]);
    }
}
