<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class HerbFormula extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'herb_formulas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['chinese_name', 'english_name'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function herbs()
    {
        return $this->belongsToMany(
            'App\Models\Herb',
            'herb_formula_herb',
            'herb_formula_id',
            'herb_id'
        )->using('App\Models\HerbsInFormula')->withPivot('dosage_start', 'dosage_end', 'dosage_unit')->withTimestamps();
    }

    public function items()
    {
        return $this->morphToMany('App\Models\Item', 'itemable')->withTimestamps();
    }

    public function categories()
    {
        return $this->items()->where('type', 'categories');
    }

    public function formula_diagnosis()
    {
        return $this->items()->where('type', 'formula_diagnosis');
    }

    public function formula_actions()
    {
        return $this->items()->where('type', 'formula_actions');
    }

    public function signs_symptoms()
    {
        return $this->items()->where('type', 'signs_symptoms');
    }

    public function tongue_diagnosis()
    {
        return $this->items()->where('type', 'tongue_diagnosis');
    }

    public function pulse_diagnosis()
    {
        return $this->items()->where('type', 'pulse_diagnosis');
    }

    public function herb_drug_interaction()
    {
        return $this->items()->where('type', 'herb_drug_interaction');
    }

    public function toxicity_contraindications()
    {
        return $this->items()->where('type', 'toxicity_contraindications');
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
}
