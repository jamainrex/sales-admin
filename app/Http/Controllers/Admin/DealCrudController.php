<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DealRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Deal;
use App\Models\Iso;

/**
 * Class DealCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DealCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Deal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/deal');
        CRUD::setEntityNameStrings('deal', 'deals');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns
        //dd($this->crud);
        //dd();

        // change the attributes of a column
        $this->crud->addColumn([
            'name' => 'account_id', // The db column name
            'label' => "Account", // Table column heading
            'type'         => 'relationship',
            // OPTIONAL
             'entity'    => 'account', // the method that defines the relationship in your Model
             'attribute' => 'business_name', // foreign key attribute that is shown to user
             'model'     => App\Models\Account::class, // foreign key model
          ]);
          
          $this->crud->addColumn([
            'name' => 'iso_id', // The db column name
            'label' => "ISO", // Table column heading
            'type'         => 'relationship',
            // OPTIONAL
             'entity'    => 'iso', // the method that defines the relationship in your Model
             'attribute' => 'business_name', // foreign key attribute that is shown to user
             'model'     => App\Models\Iso::class, // foreign key model
          ]);

        $this->crud->addColumn([
            'name' => 'name', // The db column name
            'label' => "Deal Name", // Table column heading
            'type' => 'Text'
          ]);

        $this->crud->addColumn('submission_date');
        $this->crud->addColumn('sales_stage');

        // dropdown filter
        $this->crud->addFilter([
            'name'  => 'iso',
            'type'  => 'dropdown',
            'label' => 'ISO'
        ], Iso::all()->pluck('business_name', 'id')->all()
        , function($value) { // if the filter is active
            $this->crud->addClause('where', 'iso_id', $value);
        });
        $this->crud->addFilter([
            'name'  => 'sales_stage',
            'type'  => 'dropdown',
            'label' => 'Sales Stage'
        ], Deal::getSalesStages()
        , function($value) { // if the filter is active
            $this->crud->addClause('where', 'sales_stage', $value);
        });

        // daterange filter
        $this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'from_to',
            'label' => 'Date range'
        ],
        false,
        function ($value) { // if the filter is active, apply these constraints
             $dates = json_decode($value);
             $this->crud->addClause('where', 'submission_date', '>=', $dates->from);
             $this->crud->addClause('where', 'submission_date', '<=', $dates->to . ' 23:59:59');
        });

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
        CRUD::setValidation(DealRequest::class);

        CRUD::setFromDb(); // fields

        
        // remove multiple fields from both operations
        $this->crud->removeFields(['account_id','iso_id','name', 'sales_stage']);

        $this->crud->addField([  // Select
            'label'     => "Account",
            'type'      => 'select',
            'name'      => 'account_id', // the db column for the foreign key
         
            // optional 
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'account', 
         
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\Account", // related model
            'attribute' => 'business_name', // foreign key attribute that is shown to user
         
            // optional - force the related options to be a custom query, instead of all();
            //'options'   => (function ($query) {
             //    return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
             //}), //  you can use this to filter the results show in the select
            ]);

            $this->crud->addField([  // Select
                'label'     => "ISO",
                'type'      => 'select',
                'name'      => 'iso_id', // the db column for the foreign key
             
                // optional 
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity'    => 'iso', 
             
                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Iso", // related model
                'attribute' => 'business_name', // foreign key attribute that is shown to user
             
                // optional - force the related options to be a custom query, instead of all();
                //'options'   => (function ($query) {
                 //    return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
                 //}), //  you can use this to filter the results show in the select
                ]);

            $this->crud->addField([   // select_from_array
                'name'        => 'sales_stage',
                'label'       => "Sales Stage",
                'type'        => 'select_from_array',
                'options'     => Deal::getSalesStages(),
                'allows_null' => false,
                'default'     => 'new deal',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
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
