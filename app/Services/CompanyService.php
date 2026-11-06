<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Certification;
use App\Models\CompanyInfo;
use App\Models\CoreValue;
use App\Models\Office;

/**
 * Company text for the About and Contact pages and the site header and footer.
 */
class CompanyService
{
    public function profile(): array
    {
        $company = CompanyInfo::current();

        if (! $company) {
            throw new NotFoundException('Company info has not been set up yet.');
        }

        return [
            'company'     => $company,
            'offices'     => Office::ordered()->get(),
            'core_values' => CoreValue::ordered()->get(),
            // Only certifications switched to public; numbers and scans are never sent.
            'certifications' => Certification::public()->orderBy('sort_order')->get(),
        ];
    }
}
