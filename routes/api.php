<?php

use App\Http\Controllers\Api\CapabilityController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('app.api_version'))
    ->middleware('internal')
    ->name('api.')
    ->group(function () {

        // Home page: key numbers, featured projects, services, clients
        Route::get('home', [HomeController::class, 'show'])->name('home');

        // About and Contact pages, header and footer
        Route::get('company', [CompanyController::class, 'show'])->name('company');

        // Services pages
        Route::prefix('services')->name('services.')->controller(ServiceController::class)->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('{slug}', 'show')->where('slug', '[a-z0-9-]+')->name('show');
        });

        // Projects pages
        Route::prefix('projects')->name('projects.')->controller(ProjectController::class)->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('{slug}', 'show')->where('slug', '[a-z0-9-]+')->name('show');
        });

        // Capabilities page
        Route::get('capabilities', [CapabilityController::class, 'show'])->name('capabilities');

        // Clients page
        Route::prefix('clients')->name('clients.')->controller(ClientController::class)->group(function () {
            Route::get('/', 'list')->name('list');
        });

        // Contact form
        Route::post('inquiries', [InquiryController::class, 'create'])
            ->middleware('throttle:inquiries')
            ->name('inquiries.create');
    });
