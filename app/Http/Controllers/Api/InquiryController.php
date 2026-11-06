<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Services\InquiryService;
use App\Support\VisitorIp;
use App\Traits\ApiResponse;

/**
 * Contact form. Rate limited per visitor (see AppServiceProvider).
 */
class InquiryController extends Controller
{
    use ApiResponse;

    public function __construct(private InquiryService $inquiries) {}

    public function create(InquiryRequest $request)
    {
        return $this->handle(function () use ($request) {
            $this->inquiries->submit($request->validated(), VisitorIp::from($request));

            return $this->success([
                'message' => 'Thank you. We have received your message and will reply soon.',
            ], \ResponseCode['Created']);
        });
    }
}
