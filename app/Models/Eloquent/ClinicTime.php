<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ClinicTime extends Model
{

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = [
        'base' => 'array',
        'times' => 'array',
    ];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

}
