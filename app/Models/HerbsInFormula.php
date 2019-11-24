<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HerbsInFormula extends Pivot
{
    protected $table = 'herb_formula_herb';
    protected $attributes = [
        'dosage_unit' => 'mg',
    ];
    protected $appends = ['dosage', 'dosage_with_unit'];

    public function getDosageAttribute()
    {
        return $this->dosage_start . '-' . $this->dosage_end;
    }

    public function getDosageWithUnitAttribute()
    {
        return $this->dosage_start . $this->dosage_unit . '-' . $this->dosage_end . $this->dosage_unit;
    }

    public function setDosageAttribute($dosage)
    {
        list($dosage_start, $dosage_end) = explode('-', $dosage);
        $this->dosage_start = $dosage_start;
        $this->dosage_end = $dosage_end;
    }
}
