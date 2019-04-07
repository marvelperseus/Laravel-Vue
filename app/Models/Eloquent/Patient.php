<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{

    use Notifiable;

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['status_label'];

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = ['contact_way' => 'array'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return config('const.patient_status.' . $this->status . '.label');
    }

}
