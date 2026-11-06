<?php

use App\Http\Controllers\Api\ClientController;
use Illuminate\Support\Facades\Route;

/*
 * Internal API for the SvelteKit public site: /api/v1.0/...
 * Every route needs the X-Internal-Token header (see EnsureInternalToken).
 */
Route::prefix(config('app.api_version'))
    ->middleware('internal')
    ->group(function () {

        // Clients Digital Avenue has worked for (Clients page, Home page logos)
        Route::prefix('clients')->name('clients.')->controller(ClientController::class)->group(function () {
            Route::get('/', 'list')->name('list');
        });

        // Services, projects, capabilities, company info, inquiries...
    });
