<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

/**
 * Equipment lists from the profile PDF (pages 6 and 8). All items are listed "In Order".
 * Rows: [name, capacity, own, hire, unit]
 */
class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'general' => ['name' => 'General equipment', 'items' => [
                ['Concrete Mixer Machine with Hopper', '15 Cft', 4, 0, 'Nos'],
                ['Concrete Mixer Machine (Diesel)', '10 Cft', 6, 0, 'Nos'],
                ['Wheel Loader', '3 Ton', 2, 1, 'Nos'],
                ['Soil Compactor (Roller)', '10 Ton', 1, 1, 'Nos'],
                ['Long Boom Excavator', '0.5 m³', 2, 4, 'Nos'],
                ['Excavator', '0.9 m³', 3, 1, 'Nos'],
                ['Excavator', '0.7 m³', 3, 1, 'Nos'],
                ['Excavator', '0.5 m³', 6, 1, 'Nos'],
                ['Drum Truck', '10 Wheel', 7, 4, 'Nos'],
                ['Crawler Crane', '100 Tons', 1, 0, 'Nos'],
                ['Crawler Crane', '50 Tons', 1, 0, 'Nos'],
                ['Hydraulic Crane', '25 Tons', 1, 0, 'Nos'],
                ['Hydraulic Crane', '35 Tons', 1, 0, 'Nos'],
                ['Hydraulic Tractor with Trolley', '100/150 Cft', 4, 4, 'Nos'],
                ['Lowbed', '12 Wheel', 1, 2, 'Nos'],
                ['Piling Rig Equipment', null, 2, 6, 'Sets'],
                ['Barge', null, 0, 5, 'Nos'],
                ['Dredger', '14"/18"/20"', 3, 3, 'Nos'],
                ['Drum Truck', '10 Wheel', 6, 0, 'Nos'],
                ['Drum Truck', '6 Wheel', 10, 0, 'Nos'],
                ['Generator (Diesel)', '50/32/25/15 kW', 6, 0, 'Nos'],
                ['Air Compressor', null, 1, 0, 'Nos'],
                ['Plate Compactor', null, 1, 0, 'Nos'],
                ['Diesel Pump', '4/6/8 HP', 6, 0, 'Nos'],
                ['Mud Pump', null, 12, 0, 'Nos'],
                ['Submersible Pump', null, 6, 0, 'Nos'],
                ['Welding Machine', '220V/380V', 10, 0, 'Nos'],
                ['Casing Pipe, 20 ft long', null, 2, 0, 'Sets'],
                ['Rod Cutter', null, 2, 0, 'Nos'],
                ['Vibrator Machine (Diesel/Electrical)', '2/4/6 HP', 5, 0, 'Nos'],
                ['Saloon Car', null, 3, 0, 'Nos'],
                ['Motorcycle', null, 6, 0, 'Nos'],
                ['Total Station and Survey Instrument', null, 3, 0, 'Sets'],
                ['Safety Helmets', null, 50, 0, 'Nos'],
                ['Safety Vest', null, 300, 0, 'Nos'],
                ['Safety Belts', null, 50, 0, 'Nos'],
                ['Grouting Pump', null, 0, 2, 'Sets'],
                ['Lifting and Shifting Jetty', null, 0, 2, 'Sets'],
                ['Steel Shuttering Material', null, 36000, 20000, 'sqm'],
                ['Jack Pipe', null, 650, 0, 'Nos'],
                ['Water Tank', null, 5, 0, 'Nos'],
            ]],
            'laboratory' => ['name' => 'Laboratory equipment', 'items' => [
                ['Universal Compression Testing Machine (Cylinder Crusher)', null, 3, 0, 'Nos'],
                ['Concrete Cube Mould, 6 inches', null, 12, 0, 'Nos'],
                ['Slump Cone with Tamping Rod', null, 4, 0, 'Sets'],
                ['Sieves of different sizes', null, 4, 0, 'Sets'],
                ['Solution Balance', null, 1, 0, 'Nos'],
                ['Balance (USA)', null, 1, 0, 'Nos'],
                ['Cube Mould, brass', null, 1, 0, 'Nos'],
                ['Vicat Apparatus', null, 1, 0, 'Nos'],
                ['Sample Tray', '2\' x 2\' x 4"', 2, 0, 'Nos'],
                ['Sample Tray', '1.5\' x 1.5\' x 4"', 1, 0, 'Nos'],
                ['Hammer (Power/Manual)', '2.5–5 lbs', 4, 0, 'Nos'],
                ['ACB Apparatus', null, 1, 0, 'Sets'],
                ['Oven', null, 1, 0, 'Nos'],
            ]],
            'electrical' => ['name' => 'Electrical equipment', 'items' => [
                ['Power Meter', null, 2, 0, 'Nos'],
                ['Optical Power Meter', null, 2, 0, 'Nos'],
                ['GPS', null, 4, 0, 'Nos'],
                ['Laptop', null, 30, 0, 'Nos'],
                ['Digital Camera', null, 10, 0, 'Nos'],
                ['Compass', null, 15, 0, 'Nos'],
                ['Binocular', null, 10, 0, 'Nos'],
                ['Service Tool Box', null, 10, 0, 'Nos'],
                ['Vacuum Cleaner', null, 15, 0, 'Nos'],
                ['Cutting Machine (Raised Floor)', null, 3, 0, 'Nos'],
                ['Digital Earth Tester', null, 25, 0, 'Nos'],
                ['Safety Tools (First Aid Box, Safety Belt, etc.)', null, 20, 0, 'Nos'],
                ['Sedan Car', null, 2, 0, 'Nos'],
            ]],
        ];

        $categoryOrder = 0;

        foreach ($categories as $slug => $category) {
            $model = EquipmentCategory::updateOrCreate(['slug' => $slug], [
                'name'       => $category['name'],
                'sort_order' => ++$categoryOrder,
            ]);

            foreach ($category['items'] as $i => [$name, $capacity, $own, $hire, $unit]) {
                Equipment::updateOrCreate(
                    ['category_id' => $model->id, 'sort_order' => $i + 1],
                    [
                        'name'      => $name,
                        'capacity'  => $capacity,
                        'qty_own'   => $own,
                        'qty_hire'  => $hire,
                        'unit'      => $unit,
                        'condition' => 'In Order',
                    ],
                );
            }
        }
    }
}
