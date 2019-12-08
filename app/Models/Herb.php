<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Herb extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'herbs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $attributes = [
        'dosage_unit' => 'g',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['dosage', 'dosage_with_unit', 'dropbox_herb_image', 'dropbox_constituent_images'];

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

    /**
     * Get all of the tags for the post.
     */
    public function items()
    {
        return $this->morphToMany('App\Models\Item', 'itemable')->withTimestamps();
    }

    public function categories()
    {
        return $this->items()->where('type', 'categories');
    }

    public function properties()
    {
        return $this->items()->where('type', 'properties');
    }

    public function systems_affected()
    {
        return $this->items()->where('type', 'systems_affected');
    }

    public function actions()
    {
        return $this->items()->where('type', 'actions');
    }

    public function chemical_composition()
    {
        return $this->items()->where('type', 'chemical_composition');
    }

    public function pharmacology()
    {
        return $this->items()->where('type', 'pharmacology');
    }

    public function antibiotic_strains()
    {
        return $this->items()->where('type', 'antibiotic_strains');
    }

    public function hormones()
    {
        return $this->items()->where('type', 'hormones');
    }

    public function signs_symptoms()
    {
        return $this->items()->where('type', 'signs_symptoms');
    }

    public function herb_herb_interaction()
    {
        return $this->items()->where('type', 'herb_herb_interaction');
    }

    public function herb_drug_interaction()
    {
        return $this->items()->where('type', 'herb_drug_interaction');
    }

    public function toxicity_contraindications()
    {
        return $this->items()->where('type', 'toxicity_contraindications');
    }

    public function formulas()
    {
        return $this->belongsToMany(
            'App\Models\HerbFormula',
            'herb_formula_herb',
            'herb_id',
            'herb_formula_id'
        )->using('App\Models\HerbsInFormula')->withPivot('dosage_start', 'dosage_end', 'dosage_unit')->withTimestamps();
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
    public function getDosageAttribute()
    {
        return $this->dosage_start . '-' . $this->dosage_end;
    }

    public function getDosageWithUnitAttribute()
    {
        return $this->dosage_start . $this->dosage_unit . '-' . $this->dosage_end . $this->dosage_unit;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setDosageAttribute($dosage)
    {
        list($dosage_start, $dosage_end) = explode('-', $dosage);
        $this->dosage_start = $dosage_start;
        $this->dosage_end = $dosage_end;
    }

    public function getDropboxHerbImageAttribute()
    {
        if ($this->attributes['herb_image']) {
            return \Storage::disk('dropbox')->url($this->attributes['herb_image']);
        } else {
            return null;
        }
    }

    public function getDropboxConstituentImagesAttribute()
    {
        $constituent_images = json_decode($this->attributes['constituent_images']);

        return array_map(function ($image) {
            return \Storage::disk('dropbox')->url($image);
        }, $constituent_images);
    }


    public function setHerbImageAttribute($value)
    {
        $attribute_name = "herb_image";
        $disk = "dropbox";
        $destination_path = "herbs";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setConstituentImagesAttribute($value)
    {
        $attribute_name = "constituent_images";
        $disk = "dropbox";
        $destination_path = "constituents";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
