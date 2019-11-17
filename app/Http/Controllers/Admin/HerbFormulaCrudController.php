<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HerbFormulaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HerbFormulaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class HerbFormulaCrudController extends CrudController
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
        ['Formula Diagnosis', 'formula_diagnosis'],
        ['Formula Actions', 'formula_actions'],
        ['Signs / Symptoms', 'signs_symptoms'],
        ['Tongue Diagnosis', 'tongue_diagnosis'],
        ['Pulse Diagnosis', 'pulse_diagnosis'],
        ['Toxicity / Contraindications', 'toxicity_contraindications'],
        ['Herb-Drug interaction', 'herb_drug_interaction']
    ];

    public function setup()
    {
        $this->crud->setModel('App\Models\HerbFormula');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/herb-formula');
        $this->crud->setEntityNameStrings('herb formula', 'herb formulas');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HerbFormulaRequest::class);

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
            'label'     => 'Herbs in Formula',
            'type'      => 'itemable',
            'name'      => 'herbs',
            'entity'    => 'herbs',
            'attribute' => 'english_name',
            'model'     => "App\Models\Herb",
            'pivot'     => true,
            'options'   => (function ($query) {
                return $query->orderBy('english_name', 'ASC')->get();
            }),
            'tab' => 'Herbs',
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
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        $this->withNonExistingItems();

        return $this->traitStore();
    }

    public function update()
    {
        $this->withNonExistingItems();

        return $this->traitUpdate();
    }
}
