<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Validation\Rule;

/**
 * GET /projects?service=river-dredging-and-bank-protection&client_type=un_agency&featured=1&page=2
 */
class ProjectListRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'service'     => ['nullable', 'string', Rule::exists('services', 'slug')->where('is_published', true)],
            'client_type' => ['nullable', Rule::in(Client::TYPES)],
            'featured'    => ['nullable', 'boolean'],
            'per_page'    => ['nullable', 'integer', 'between:1,50'],
            'page'        => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function filters(): array
    {
        return [
            'service'     => $this->validated('service'),
            'client_type' => $this->validated('client_type'),
            'featured'    => $this->boolean('featured'),
        ];
    }
}
