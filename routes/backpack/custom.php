<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('herb', 'HerbCrudController');
    Route::crud('herb-formula', 'HerbFormulaCrudController');
    Route::crud('admin', 'AdminCrudController');
    Route::crud('submission', 'SubmissionCrudController');
    Route::crud('adminsignssymptoms', 'AdminSignsSymptomsCrudController');
    Route::crud('adminchemicalcompositions', 'AdminChemicalCompositionsCrudController');
}); // this should be the absolute last line of this file