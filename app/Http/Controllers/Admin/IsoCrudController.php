<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IsoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IsoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IsoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Iso::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/iso');
        CRUD::setEntityNameStrings('iso', 'iso');
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

        $this->crud->removeColumn('emails');
        $this->crud->addColumn([
            'name'        => 'emails', // The db column name
            'label'       => 'Emails', // Table column heading
            'type'        => 'multidimensional_array',
            'visible_key' => 'email' // The key to the attribute you would like shown in the enumeration
        ]);
        //$this->crud->enableDetailsRow();

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

        CRUD::setValidation(IsoRequest::class);

        //CRUD::setFromDb(); // fields
        $this->crud->addField(
            [   // Text
            'name'  => 'business_name',
            'label' => "Business Name",
            'type'  => 'text',

             ]);

            $this->crud->addField([   // Text
                'name'  => 'contact_name',
                'label' => "Contact Name",
                'type'  => 'text',
    
             ]);

             $this->crud->addField([   // Text
                'name'  => 'contact_number',
                'label' => "Contact Number",
                'type' => 'text',
    
             ]);

            $this->crud->addField([   // repeatable
                'name'  => 'emails',
                'label' => 'Emails',
                'type'  => 'repeatable',
                'fields' => [
                    [
                        'name'    => 'email',
                        'type'    => 'email',
                        //'label'   => 'Email',
                        'wrapper' => ['class' => 'form-group col-md-8'],
                    ],
                ],
            
                // optional
                'new_item_label'  => 'Add Email', // customize the text of the button
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

    // public function store()
    // {
    //     // do something before validation, before save, before everything; for example:
    //     // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
    // // $this->crud->removeField('password_confirmation');

    // // Note: By default Backpack ONLY saves the inputs that were added on page using Backpack fields.
    // // This is done by stripping the request of all inputs that do NOT match Backpack fields for this
    // // particular operation. This is an added security layer, to protect your database from malicious
    // // users who could theoretically add inputs using DeveloperTools or JavaScript. If you're not properly
    // // using $guarded or $fillable on your model, malicious inputs could get you into trouble.

    // // However, if you know you have proper $guarded or $fillable on your model, and you want to manipulate 
    // // the request directly to add or remove request parameters, you can also do that.
    // // We have a config value you can set, either inside your operation in `config/backpack/crud.php` if
    // // you want it to apply to all CRUDs, or inside a particular CrudController:
    //     // $this->crud->setOperationSetting('saveAllInputsExcept', ['_token', '_method', 'http_referrer', 'current_tab', 'save_action']);
    // // The above will make Backpack store all inputs EXCEPT for the ones it uses for various features.
    // // So you can manipulate the request and add any request variable you'd like.
    // // $this->crud->getRequest()->request->add(['author_id'=> backpack_user()->id]);
    // // $this->crud->getRequest()->request->remove('password_confirmation');

    //     //dd($this->crud->getRequest()->all());

    //     $data = $this->crud->getRequest()->all();

    //     //dd(json_decode($data['emails']));

    //     $response = $this->traitStore();
    //     // do something after save
    //     return $response;
    // }

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
