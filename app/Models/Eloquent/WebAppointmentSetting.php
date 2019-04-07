<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class WebAppointmentSetting extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['unit_items'];

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = [
        'units' => 'array',
        'target' => 'array',
        'web_cancel_flag' => 'boolean'
    ];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * set Unit Name Array.
     *
     * @param  array $value
     * @return void
     */
    public function setUnitItemsAttribute($value)
    {
        $this->attributes['unit_items'] = $value;
    }

    /**
     * get Unit Name Array.
     *
     * @return array
     */
    public function getUnitItemsAttribute()
    {
        return (array_key_exists('unit_items', $this->attributes)) ? $this->attributes['unit_items'] : array();
    }

}
