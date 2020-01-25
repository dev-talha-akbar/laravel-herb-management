<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminSignsSymptomsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdminSignsSymptomsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdminSignsSymptomsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\AdminSignsSymptoms');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adminsignssymptoms');
        $this->crud->denyAccess(['create', 'update', 'show', 'delete']);
        $this->crud->allowAccess(['list']);
        $this->crud->setEntityNameStrings('Signs & Symptoms', 'Signs & Symptoms');
    }

    protected function setupListOperation()
    {
        $this->crud->removeAllColumns();

        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => "Name",
            ], [
                'name' => 'group',
                'label' => "Group",
                'type' => 'signs-symptoms-group'
            ],
        ]);
        $this->crud->removeAllButtons();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminSignsSymptomsRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
