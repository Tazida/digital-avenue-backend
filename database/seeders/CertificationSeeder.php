<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

/**
 * Licences and enlistments (profile PDF pages 12 and 15).
 * Certificate numbers are NOT copied into the code: the client enters them in the admin panel.
 */
class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        Certification::updateOrCreate(['title' => 'Enlisted supplier of the Directorate General of Defence Purchase (DGDP)'], [
            'issuer'     => 'Directorate General of Defence Purchase, Ministry of Defence',
            'issued_on'  => '2025-09-14',
            'expires_on' => null,
            'is_public'  => true,
            'sort_order' => 1,
        ]);

        // Expired on 30 June 2026: hidden until the client sends the renewed licence.
        Certification::updateOrCreate(['title' => 'Trade licence'], [
            'issuer'     => null,
            'issued_on'  => null,
            'expires_on' => '2026-06-30',
            'is_public'  => false,
            'sort_order' => 2,
        ]);
    }
}
