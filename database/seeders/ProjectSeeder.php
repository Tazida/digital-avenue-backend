<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seed projects from the work orders and completion certificates in the profile PDF
 * (pages 18-115), newest first on the site.
 *
 * Privacy rules (see the project docs):
 * - contract_value stays empty; amounts and rates are never copied from the PDF
 * - no names, phones or emails of people at client companies
 * - titles avoid political names; the client confirms the final titles before launch
 *
 * Dates: exact where the PDF gives them. Where it only gives a year, the project starts on
 * 1 January of that year and ends on 31 December of its last year (the site shows years).
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->projects() as $data) {
            $client = Client::where('name', $data['client'])->firstOrFail();
            $services = Service::whereIn('slug', array_map(fn ($t) => Str::slug($t), $data['services']))->pluck('id');

            $project = Project::updateOrCreate(['slug' => $data['slug']], [
                'client_id'      => $client->id,
                'title'          => $data['title'],
                'location'       => $data['location'],
                'start_date'     => $data['start'],
                'end_date'       => $data['end'],
                'status'         => 'completed',
                'summary'        => $data['summary'],
                'scope'          => $data['scope'] ?? null,
                'contract_value' => null,
                'show_value'     => false,
                'is_featured'    => $data['featured'] ?? false,
                'is_published'   => true,
            ]);

            $project->services()->sync($services);
        }
    }

    private function projects(): array
    {
        return [
            [
                'slug'     => 'biwta-dredger-hulls-barges-tug-boats',
                'title'    => 'Steelwork for BIWTA dredger hulls (CSD-450, CSD-650), barges and tug boats',
                'client'   => 'Karnafuly Dry Dock Ltd.',
                'location' => 'Anowara, Chattogram',
                'start'    => '2017-09-17',
                'end'      => '2024-01-20',
                'summary'  => 'Steel fabrication at Karnafuly Dry Dock for the BIWTA 20-dredger project: dredger hulls, barges, tug boats and side pontoons.',
                'scope'    => implode("\n", [
                    'BIWTA dredger project GD-1 and GD-2 (Sep 2017 – Jan 2019)',
                    'BIWTA dredger project GD-3 and GD-4 (Jan 2018 – Sep 2019)',
                    'Oil and water barges, GD-3 and GD-4 (Jan 2018 – Sep 2020)',
                    'Side pontoons for 4 dredgers, construction and assembly (Aug 2018 – Jan 2019)',
                    '20-tonne bollard-pull tug boats, GD-3 and GD-4 (Jan 2022 – May 2022)',
                    'New dredger CSD-650 mm (Mar 2022 – Jan 2024)',
                ]),
                'services' => ['Mechanical and Electrical Installations'],
                'featured' => true,
            ],
            [
                'slug'     => 'mv-bayone-forward-extension',
                'title'    => 'MV Bayone cruise ship forward extension, steelwork',
                'client'   => 'Karnafuly Dry Dock Ltd.',
                'location' => 'Anowara, Chattogram',
                'start'    => '2022-07-30',
                'end'      => '2022-11-30',
                'summary'  => 'Steelwork for the forward extension of the cruise ship MV Bayone at Karnafuly Dry Dock.',
                'services' => ['Mechanical and Electrical Installations'],
            ],
            [
                'slug'     => 'nct-cct-jetty-maintenance-dredging',
                'title'    => 'Maintenance dredging at NCT and CCT jetty, Karnaphuli river (CPA port limit)',
                'client'   => 'E-Engineering Ltd. (EEL)',
                'location' => 'Chattogram',
                'start'    => '2021-05-24',
                'end'      => null,
                'summary'  => 'Maintenance dredging with suction dredgers opposite the NCT and CCT jetties, at the turning basin of the Karnaphuli River within Chattogram port limits, for the Chittagong Port Authority (CPA).',
                'services' => ['River Dredging and Bank Protection'],
                'featured' => true,
            ],
            [
                'slug'     => 'bns-pekua-sand-and-geo-tube-filling',
                'title'    => 'Sand filling and geo-tube filling by dredging, BNS project',
                'client'   => 'MATS Corporation',
                'location' => "Pekua, Cox's Bazar",
                'start'    => '2020-08-10',
                'end'      => '2021-01-05',
                'summary'  => 'Sand filling and geo-tube filling by dredging at the BNS project site in Pekua.',
                'services' => ['Land Reclamation and Development', 'Geo-bag Filling and Placement'],
            ],
            [
                'slug'     => 'bns-pekua-sand-filling-2021',
                'title'    => 'Sand filling by dredging, BNS project (2021)',
                'client'   => 'Confidence Electro-Mech Ltd.',
                'location' => "Pekua, Cox's Bazar",
                'start'    => '2021-01-02',
                'end'      => null,
                'summary'  => 'Sand filling by dredging at the BNS project site in Pekua.',
                'services' => ['Land Reclamation and Development'],
            ],
            [
                'slug'     => 'maritime-university-sand-filling',
                'title'    => 'Sand filling, maritime university site',
                'client'   => 'M/S Mawa Enterprise',
                'location' => 'Hamid Char, Chattogram',
                'start'    => '2019-01-01', // year only in the PDF
                'end'      => '2019-12-31',
                'summary'  => 'Sand filling for the maritime university site at Hamid Char.',
                'services' => ['Land Reclamation and Development'],
            ],
            [
                'slug'     => 'wfp-construction-equipment-materials-supply',
                'title'    => 'Construction equipment and materials supply (long-term agreement)',
                'client'   => 'World Food Programme (WFP)',
                'location' => "Cox's Bazar, Ukhia, Teknaf",
                'start'    => '2018-01-01', // year only
                'end'      => '2019-12-31',
                'summary'  => 'Supply and delivery of construction equipment and materials under a long-term agreement with WFP.',
                'services' => ['Supply and Transportation of Construction Materials'],
            ],
            [
                'slug'     => 'wfp-machinery-hire-heavy-machine-operators',
                'title'    => 'Construction machinery hire and heavy machine operators (long-term agreements)',
                'client'   => 'World Food Programme (WFP)',
                'location' => 'Kutupalong camps, Ukhia',
                'start'    => '2018-01-01', // year only
                'end'      => '2019-12-31',
                'summary'  => 'Hire of construction machinery with heavy machine operators for site maintenance in the Kutupalong refugee camps.',
                'services' => ['Supply and Operation of Heavy Machinery, Vehicles and Skilled Operators'],
                'featured' => true,
            ],
            [
                'slug'     => 'iom-bulldozer-excavator-roller-rental',
                'title'    => 'Bulldozer, excavator and roller rental',
                'client'   => 'International Organization for Migration (IOM)',
                'location' => "Cox's Bazar",
                'start'    => '2018-01-01', // year only
                'end'      => '2018-12-31',
                'summary'  => 'Rental of bulldozers, excavators and rollers for IOM in Cox\'s Bazar.',
                'services' => ['Supply and Operation of Heavy Machinery, Vehicles and Skilled Operators'],
            ],
            [
                'slug'     => 'unhcr-office-equipment-supply',
                'title'    => 'Office equipment supply',
                'client'   => 'UNHCR, the UN Refugee Agency',
                'location' => "Cox's Bazar",
                'start'    => '2018-01-01', // year only
                'end'      => '2018-12-31',
                'summary'  => 'Supply of office equipment to UNHCR in Cox\'s Bazar.',
                'services' => [],
            ],
            [
                'slug'     => 'karnaphuli-tunnel-sand-dredging-filling',
                'title'    => 'Sand dredging and filling, Karnaphuli multi-lane road tunnel',
                'client'   => 'China Communications Construction Company (CCCC)',
                'location' => 'Chattogram',
                'start'    => '2018-01-12',
                'end'      => null,
                'summary'  => 'Sand dredging and filling under a subcontract with CCCC for the Karnaphuli multi-lane road tunnel.',
                'services' => ['River Dredging and Bank Protection', 'Land Reclamation and Development'],
                'featured' => true,
            ],
        ];
    }
}
