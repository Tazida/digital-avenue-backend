<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * The 13 integrated areas of operation from the profile PDF (page 5).
 * Summaries and descriptions are left empty: the client writes them in the admin panel.
 */
class ServiceSeeder extends Seeder
{
    public const SERVICES = [
        // category => titles, in display order
        'dredging_land' => [
            'River Dredging and Bank Protection',
            'Land Reclamation and Development',
            'Geo-bag Filling and Placement',
            'Hill Cutting, Stabilization and Slope Protection',
            'Dam and Embankment Construction',
        ],
        'civil_infrastructure' => [
            'Civil and Infrastructure Construction',
            'Road Construction in Remote and Challenging Terrains',
            'Warehouse and Storage Facility Construction',
            'Infrastructure Development for Industrial Projects',
        ],
        'mechanical_electrical' => [
            'Mechanical and Electrical Installations',
            'Heavy-duty Pipeline Installation',
        ],
        'machinery_supply' => [
            'Supply and Operation of Heavy Machinery, Vehicles and Skilled Operators',
            'Supply and Transportation of Construction Materials',
        ],
    ];

    public function run(): void
    {
        $order = 0;

        foreach (self::SERVICES as $category => $titles) {
            foreach ($titles as $title) {
                Service::updateOrCreate(['slug' => Str::slug($title)], [
                    'title'        => $title,
                    'category'     => $category,
                    'sort_order'   => ++$order,
                    'is_published' => true,
                ]);
            }
        }
    }
}
