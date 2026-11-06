<?php

namespace App\Traits;

use App\Exceptions\DAException;
use App\Exceptions\NotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * One response shape for the whole API:
 *   success → { "success": true, ...data }
 *   error   → { "error": true, "message": "..." }
 */
trait ApiResponse
{
    protected function success(array $data = [], int $status = \ResponseCode['Success']): JsonResponse
    {
        return response()->json(['success' => true] + $data, $status);
    }

    protected function error(string $message, int $status): JsonResponse
    {
        return response()->json([
            'error'   => true,
            'message' => $message,
        ], $status);
    }

    protected function serverError(\Throwable $e): JsonResponse
    {
        Log::error($e);

        return $this->error('Sorry, something went wrong.', \ResponseCode['Internal Server Error']);
    }

    /**
     * Runs a controller action and turns service exceptions into API errors:
     * NotFoundException → 404, other DAException → 409, anything else → 500 (logged).
     */
    protected function handle(callable $action): JsonResponse
    {
        try {
            return $action();
        } catch (NotFoundException $e) {
            return $this->error($e->getMessage(), \ResponseCode['Not Found']);
        } catch (DAException $e) {
            return $this->error($e->getMessage(), \ResponseCode['Conflict']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }
}
