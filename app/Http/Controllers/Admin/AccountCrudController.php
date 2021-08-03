<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Account::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account');
        CRUD::setEntityNameStrings('account', 'accounts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns
        $this->crud->removeColumns(['owners', 'sic_id']);

        $this->crud->addColumn([
            'name' => 'sic_id', // The db column name
            'label' => "SIC", // Table column heading
            'type'         => 'relationship',
            // OPTIONAL
             'entity'    => 'industry', // the method that defines the relationship in your Model
             'attribute' => 'description', // foreign key attribute that is shown to user
             'model'     => App\Models\Sic::class, // foreign key model
          ]);
        $this->crud->addColumn([
            'name'        => 'owners', // The db column name
            'label'       => 'Owners', // Table column heading
            'type'        => 'multidimensional_array',
            'visible_key' => 'name' // The key to the attribute you would like shown in the enumeration
        ]);

        
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AccountRequest::class);

        //CRUD::setFromDb(); // fields

        $this->crud->addField([   // Text
            'name'  => 'business_name',
            'label' => "Business Name",
            'type'  => 'text',

             ]);
        $this->crud->addField([  // Select
            'label'     => "Industry",
            'type'      => 'select',
            'name'      => 'sic_id', // the db column for the foreign key
         
            // optional 
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'industry', 
         
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\Sic", // related model
            'attribute' => 'description', // foreign key attribute that is shown to user
         
            // optional - force the related options to be a custom query, instead of all();
            //'options'   => (function ($query) {
             //    return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
             //}), //  you can use this to filter the results show in the select
            ]);

        $this->crud->addField([   // repeatable
            'name'  => 'owners',
            'label' => 'Owners',
            'type'  => 'repeatable',
            'fields' => [
                [
                    'name'    => 'name',
                    'type'    => 'text',
                    'label'   => 'Name',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name'    => 'title',
                    'type'    => 'text',
                    'label'   => 'Title',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name'    => 'birthdate',
                    'type'  => 'date_picker',
                    'label'   => 'Date of Birth',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
            ],
        
            // optional
            'new_item_label'  => 'Add Owner', // customize the text of the button
            //'init_rows' => 2, // number of empty rows to be initialized, by default 1
            //'min_rows' => 2, // minimum rows allowed, when reached the "delete" buttons will be hidden
            //'max_rows' => 2, // maximum rows allowed, when reached the "new item" button will be hidden
        
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
