<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base for every API form request. Access is already checked by route middleware,
 * and validation errors are rendered as { "error": true, "message": {...} } with 422
 * in bootstrap/app.php, so requests only define rules() (and messages() if needed).
 */
abstract class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
