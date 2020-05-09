<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminChemicalCompositionsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdminChemicalCompositionsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdminChemicalCompositionsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\AdminChemicalCompositions');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adminchemicalcompositions');
        $this->crud->denyAccess(['show']);
        $this->crud->allowAccess(['list', 'create', 'update', 'delete']);
        $this->crud->setEntityNameStrings('Chemical Compositions', 'Chemical Compositions');
    }

    protected function setupListOperation()
    {

        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => "Name",
            ]
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminChemicalCompositionsRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Name',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {destroy as traitDestroy;}

    function destroy($id)
    {
        return \App\Models\Item::where('id', $id)->delete();
    }
}
