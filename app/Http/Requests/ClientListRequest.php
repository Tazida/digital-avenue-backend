<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Validation\Rule;

/**
 * GET /clients?type=un_agency — optional filter by client type.
 */
class ClientListRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type' => ['nullable', Rule::in(Client::TYPES)],
        ];
    }
}
