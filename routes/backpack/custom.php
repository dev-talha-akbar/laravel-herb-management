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
    Route::crud('adminproperties', 'AdminPropertiesCrudController');
    Route::crud('adminsystemsaffected', 'AdminSystemsAffectedCrudController');
    Route::crud('adminactions', 'AdminActionsCrudController');
    Route::crud('adminpharmacology', 'AdminPharmacologyCrudController');
    Route::crud('adminantibioticstrains', 'AdminAntibioticStrainsCrudController');
    Route::crud('adminhormones', 'AdminHormonesCrudController');
    Route::crud('adminherbherbinteraction', 'AdminHerbHerbInteractionCrudController');
    Route::crud('adminherbdruginteraction', 'AdminHerbDrugInteractionCrudController');
    Route::crud('admintoxicitycontraindications', 'AdminToxicityContraindicationsCrudController');
}); // this should be the absolute last line of this file