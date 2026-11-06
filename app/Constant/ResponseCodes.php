<?php

namespace App\Constant;

/*
 * HTTP status codes used by every API response. Loaded globally through
 * composer.json "autoload.files", so use it as \ResponseCode["Not Found"].
 */
define('ResponseCode', [
    'Success'               => 200,
    'Created'               => 201,
    'Bad Request'           => 400,
    'Unauthorized'          => 401,
    'Forbidden'             => 403,
    'Not Found'             => 404,
    'Method Not Allowed'    => 405,
    'Conflict'              => 409,
    'Unprocessable Content' => 422,
    'Too Many Requests'     => 429,
    'Internal Server Error' => 500,
]);
