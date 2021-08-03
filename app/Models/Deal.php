<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'deals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function getSalesStages()
    {
        return [
            'new deal' => 'New Deal', 
            'missing info'=> 'Missing Info', 
            'deal won' => 'Deal Won', 
            'deal lost' => 'Deal Lost'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function setName()
    {
        $dealCount = Deal::where('account_id', $this->account_id)->count();
        $this->name = $this->account->business_name . ' ' . ( $dealCount + 1 );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function iso()
    {
        return $this->belongsTo(Iso::class, 'iso_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ( $deal )
            {
                $deal->setName();
            }
        );
    }
}
