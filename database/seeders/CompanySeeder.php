<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use App\Models\CoreValue;
use App\Models\Office;
use Illuminate\Database\Seeder;

/**
 * Company text, offices and core values from the profile PDF (pages 2, 3, 14, 15).
 */
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $info = CompanyInfo::current() ?? new CompanyInfo;
        $info->fill([
            'name'    => 'Digital Avenue',
            'tagline' => null, // one-line pitch for the Home page: to be written with the client
            'intro'   => implode("\n\n", [
                'Digital Avenue is a leading construction and engineering company in Bangladesh, founded and led by Mr Abu Sadat Anwarul Karim. Since its establishment, the company has earned a strong reputation for delivering high-quality construction, infrastructure development, and maintenance services throughout the country.',
                "The company is committed to contributing to the sustainable development of Bangladesh through the successful implementation of projects in both the public and private sectors. Digital Avenue strives to promote excellence in the construction industry by adopting innovative engineering solutions and maintaining compliance with national and international standards. Its operations are aligned with the country's economic priorities, environmental conditions, socio-economic realities, and community needs.",
                "With a highly qualified and experienced workforce, Digital Avenue possesses the technical expertise and organizational capacity required to execute projects efficiently and effectively. The company's management team consists of skilled professionals with extensive industry experience, supported by competent engineers, technical personnel, and administrative staff. This combination of strong leadership and technical capability enables the company to deliver projects within budget, on schedule, and to the highest quality standards.",
                "Digital Avenue has successfully undertaken a wide range of construction and infrastructure projects, including assignments in challenging and remote environments. Notably, the company has gained extensive experience in the construction and maintenance of facilities within the world's largest refugee settlement in Ukhia, Cox's Bazar. In this capacity, Digital Avenue has worked closely with SMAP (Site Maintenance Engineering Project), an engineering initiative managed by internationally recognized humanitarian organizations, including the World Food Programme (WFP), the United Nations High Commissioner for Refugees (UNHCR), and the International Organization for Migration (IOM).",
                "Digital Avenue is an enlisted supplier of the Directorate General of Defence Purchase (DGDP) under the Bangladesh Army and has successfully supplied products and services for various defense-related projects. This recognition reflects the company's commitment to quality, reliability, and professional excellence.",
            ]),
            'vision'  => 'To be a leading engineering solutions provider in Bangladesh, recognised for innovation, quality, and sustainable growth.',
            'mission' => implode("\n", [
                'To deliver high-quality engineering and infrastructure projects that meet international standards.',
                'To invest in advanced technology and skilled human resources to foster sustainable development.',
                'To uphold the highest standards of safety, integrity, and professionalism in every project.',
            ]),
            'founder_message' => implode("\n\n", [
                "The company's Founder, Mr Abu Sadat Anwarul Karim, earned his MA in Marketing Communication from Middlesex University Business School, United Kingdom. Prior to establishing Digital Avenue, he accumulated approximately 15 years of professional experience with international organizations in Bangladesh and abroad. Under his visionary leadership, Digital Avenue has grown into a trusted and respected construction company, successfully delivering numerous large-scale and strategically important projects across Bangladesh.",
                'Today, Digital Avenue continues to expand its capabilities and strengthen its position as a reliable partner for government agencies, international organizations, development partners, and private-sector clients seeking high-quality construction and engineering solutions.',
            ]),
            'email' => 'digitalavenuebd@gmail.com',
            'phone' => '+88 01979-992229',
        ])->save();

        $offices = [
            [
                'label'          => 'Chattogram',
                'address'        => '1126/A East Nasirabad, CDA Avenue, Chattogram-4210, Bangladesh',
                'phone'          => '+88 01979-992229',
                'is_head_office' => true, // assumption: the work orders and VAT registration use this address
                'sort_order'     => 1,
            ],
            [
                'label'          => 'Dhaka',
                'address'        => 'House #09, Road #08, Sector #01, Uttara Model Town, Dhaka-1230, Bangladesh',
                'phone'          => null,
                'is_head_office' => false,
                'sort_order'     => 2,
            ],
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(['label' => $office['label']], $office);
        }

        $values = [
            'Integrity'      => 'Conducting business with honesty and transparency.',
            'Innovation'     => 'Adopting advanced engineering methods and technologies.',
            'Quality'        => "Meeting and exceeding clients' expectations.",
            'Commitment'     => 'Completing projects on schedule and with cost efficiency.',
            'Sustainability' => 'Promoting environmentally friendly and socially responsible practices.',
        ];

        $order = 0;
        foreach ($values as $title => $description) {
            CoreValue::updateOrCreate(['title' => $title], [
                'description' => $description,
                'sort_order'  => ++$order,
            ]);
        }
    }
}
