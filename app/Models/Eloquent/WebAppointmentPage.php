<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class WebAppointmentPage extends Model
{

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = ['form_objects' => 'array'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

}
