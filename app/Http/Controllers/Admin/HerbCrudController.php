<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HerbRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Herb;

/**
 * Class HerbCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class HerbCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \App\Helpers\HasItems;

    private $itemableTypes = [
        ['Category', 'categories'],
        ['Properties', 'properties'],
        ['Systems affected', 'systems_affected'],
        ['Actions', 'actions'],
        ['Chemical composition', 'chemical_composition'],
        ['Pharmacology', 'pharmacology'],
        ['Antibiotic Strains', 'antibiotic_strains'],
        ['Hormones', 'hormones'],
        ['Signs / Symptoms', 'signs_symptoms'],
        ['Herb-Herb interaction', 'herb_herb_interaction'],
        ['Herb-Drug interaction', 'herb_drug_interaction'],
        ['Toxicity / Contraindications', 'toxicity_contraindications']
    ];

    public function setup()
    {
        $this->crud->setModel('App\Models\Herb');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/herb');
        $this->crud->setEntityNameStrings('herb', 'herbs');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'chinese_name',
            'label' => 'Chinese Name'
        ]);

        $this->crud->addColumn([
            'name' => 'english_name',
            'label' => 'English Name'
        ]);

        $this->crud->addColumn([
            'name' => 'pharmaceutical_name',
            'label' => 'Pharmaceutical Name'
        ]);

        $this->crud->addColumn([
            'name' => 'literal_name',
            'label' => 'Literal Name'
        ]);

        $this->crud->addColumn([
            'name' => 'dosage_with_unit',
            'label' => 'Dosage'
        ]);
    }

    protected function setupCreateOperation($id = null)
    {
        $this->crud->setValidation(HerbRequest::class);

        $this->crud->addField([
            'name' => 'chinese_name',
            'type' => 'text',
            'tab' => 'Basic',
        ]);

        $this->crud->addField([
            'name' => 'english_name',
            'type' => 'text',
            'tab' => 'Basic',
        ]);

        $this->crud->addField([
            'name' => 'pharmaceutical_name',
            'type' => 'text',
            'tab' => 'Basic',
        ]);

        $this->crud->addField([
            'name' => 'literal_name',
            'type' => 'text',
            'tab' => 'Basic',
        ]);

        $this->crud->addField([
            'name' => 'dosage',
            'type' => 'dosage-slider',
            'label' => 'Dosage',
            'default' => '5-20',
            'tab' => 'Basic'
        ]);

        $this->crud->addField([   // Number
            'name' => 'max_dosage',
            'label' => 'Maximum Dosage (g)',
            'type' => 'number',
            'tab' => 'Basic',
            // optionals
            'attributes' => ["step" => "any"], // allow decimals
            // 'prefix' => "$",
            // 'suffix' => ".00",
        ]);

        $this->crud->addField([   // radio
            'name'        => 'usage', // the name of the db column
            'label'       => 'Administered', // the input label
            'type'        => 'radio',
            'options'     => [
                0 => "Both Orally and Topically",
                1 => "Orally",
                2 => "Topically"
            ],
            'tab' => 'Basic'
        ]);

        foreach ($this->itemableTypes as $type) {
            $this->crud->addField([
                'label'     => $type[0],
                'type'      => 'itemable',
                'name'      => $type[1],
                'entity'    => $type[1],
                'attribute' => 'value',
                'model'     => "App\Models\Item",
                'pivot'     => true,
                'options'   => (function ($query) use ($type) {
                    return $query->orderBy('type', 'ASC')->where('type', $type[1])->get();
                }),
                'tab' => $type[1] !== 'categories' ? 'Details' : 'Basic',
            ]);
        }

        $herb_selection = [
            'label'     => 'Found in Formulas',
            'type'      => 'herb-selection',
            'name'      => 'formulas',
            'entity'    => 'formulas',
            'attribute' => 'chinese_name',
            'model'     => "App\Models\HerbFormula",
            'options'   => (function ($query) {
                return $query->orderBy('chinese_name', 'ASC')->get();
            }),
            'tab' => 'Formulas Found In',
        ];

        if ($id !== null) {
            $herb_selection['id'] = $id;
        }

        $this->crud->addField($herb_selection);

        $this->crud->addField([
            'name' => 'herb_image',
            'label' => 'Herb Image',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'dropbox',
            'tab' => 'Images'
        ]);

        $this->crud->addField([
            'name' => 'constituent_images',
            'label' => 'Constituent Images',
            'type' => 'upload_multiple',
            'upload' => true,
            'disk' => 'dropbox',
            'tab' => 'Images'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation(intval(\Route::current()->parameter('id')));
    }

    public function store()
    {
        $this->crud->validateRequest();

        list($formulas, $dosages) = $this->stripFormulasDosages();

        $this->withNonExistingItems();

        $response = $this->traitStore();

        $this->syncFormulas($formulas, $dosages);

        return $response;
    }

    public function update()
    {
        $this->crud->validateRequest();

        list($formulas, $dosages) = $this->stripFormulasDosages();

        $request = $this->crud->request;

        if ($request->has('clear_herb_image')) {
            $request->merge(['herb_image' => null]);
        }

        $this->withNonExistingItems();

        $response = $this->traitUpdate();

        $this->syncFormulas($formulas, $dosages);

        return $response;
    }

    private function stripFormulasDosages()
    {
        $request = $this->crud->request;

        $formulas = [];
        $dosages = [];

        if ($request->formulas && $request->herb_dosage) {
            $formulas = array_merge($formulas, $request->formulas);
            $dosages = array_merge($dosages, $request->herb_dosage);
        }

        $request->replace($request->except(['formulas', 'herb_dosage']));

        return [$formulas, $dosages];
    }

    private function syncFormulas($formulas, $dosages)
    {
        $herb = $this->crud->entry;
        $pivotedFormulas = [];

        foreach ($formulas as $i => $formula) {
            $pivotedFormulas[$formula] = [
                'dosage' => $dosages[$i]
            ];
        }

        $herb->formulas()->sync($pivotedFormulas);
    }
}
