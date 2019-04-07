<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class WebAppointmentByPatientSetting extends Model
{

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = [
        'units' => 'array'
    ];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

}
