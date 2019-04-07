<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ClinicHoliday extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['title', 'color'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * Get Event Title.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return config('const.holiday.' . $this->type . '.title');
    }

    /**
     * Get Event Title.
     *
     * @return string
     */
    public function getColorAttribute()
    {
        return config('const.holiday.' . $this->type . '.color');
    }

}
