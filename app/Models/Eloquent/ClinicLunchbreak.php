<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ClinicLunchbreak extends Model
{

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = [
        'base' => 'array',
        'times' => 'array',
        'display_flag' => 'boolean'
    ];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

}
