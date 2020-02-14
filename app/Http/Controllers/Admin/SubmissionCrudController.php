<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubmissionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubmissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubmissionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Submission');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/submission');
        $this->crud->setEntityNameStrings('submission', 'submissions');
        $this->crud->denyAccess(['create', 'update', 'show', 'delete']);
        $this->crud->allowAccess(['list']);
        $this->crud->addButtonFromView('line', 'view', 'submission-view', 'end');
    }

    protected function setupListOperation()
    {
        $this->crud->removeColumns(['form', 'result']);

        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => "Patient Name",
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SubmissionRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
