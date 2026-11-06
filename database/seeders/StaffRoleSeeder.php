<?php

namespace Database\Seeders;

use App\Models\StaffRole;
use Illuminate\Database\Seeder;

/**
 * Workforce by role from the profile PDF (page 7). Rows: [title, own, hire]
 */
class StaffRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['B.Sc. Engineer (Civil)', 4, 0],
            ['B.Sc. Engineer (Electrical)', 2, 0],
            ['B.Sc. Engineer (Mechanical)', 2, 0],
            ['Diploma Engineer (Civil)', 6, 0],
            ['Diploma Engineer (Electrical)', 2, 0],
            ['Diploma Engineer (Mechanical)', 2, 0],
            ['Diploma Engineer (Computer)', 1, 0],
            ['Safety Supervisor', 12, 0],
            ['Supervisor', 12, 0],
            ['Store Keeper', 4, 0],
            ['Foreman', 12, 0],
            ['Pipe Fitter', 4, 0],
            ['Welder', 2, 0],
            ['Skilled Worker', 0, 100],
            ['Unskilled Worker', 0, 200],
        ];

        foreach ($roles as $i => [$title, $own, $hire]) {
            StaffRole::updateOrCreate(['title' => $title], [
                'count_own'  => $own,
                'count_hire' => $hire,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
