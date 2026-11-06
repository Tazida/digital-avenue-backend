<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

/**
 * Clients from the profile PDF (page 16) and the work orders (pages 18-115).
 * Logos stay hidden (show_logo = false) until the client confirms permission,
 * which matters most for the UN agencies.
 */
class ClientSeeder extends Seeder
{
    public const CLIENTS = [
        ['name' => 'World Food Programme (WFP)', 'type' => 'un_agency'],
        ['name' => 'UNHCR, the UN Refugee Agency', 'type' => 'un_agency'],
        ['name' => 'International Organization for Migration (IOM)', 'type' => 'un_agency'],
        ['name' => 'UN Women', 'type' => 'un_agency'],
        ['name' => 'Directorate General of Defence Purchase (DGDP)', 'type' => 'government', 'website' => 'https://www.dgdp.gov.bd'],
        ['name' => 'China Communications Construction Company (CCCC)', 'type' => 'private'],
        ['name' => 'Karnafuly Dry Dock Ltd.', 'type' => 'private'],
        ['name' => 'E-Engineering Ltd. (EEL)', 'type' => 'private', 'website' => 'https://www.e-enggltd.com'],
        ['name' => 'MATS Corporation', 'type' => 'private'],
        ['name' => 'Confidence Electro-Mech Ltd.', 'type' => 'private'],
        ['name' => 'M/S Mawa Enterprise', 'type' => 'private'],
    ];

    public function run(): void
    {
        foreach (self::CLIENTS as $i => $client) {
            Client::updateOrCreate(['name' => $client['name']], [
                'type'       => $client['type'],
                'website'    => $client['website'] ?? null,
                'show_logo'  => false,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
