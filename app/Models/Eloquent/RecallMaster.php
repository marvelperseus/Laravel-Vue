<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RecallMaster extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['draft_flag_label', 'patient_status_label', 'timing_label'];

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = [
        'draft_flag' => 'boolean'
    ];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * Get Draft Flag Label Name.
     *
     * @return string
     */
    public function getDraftFlagLabelAttribute()
    {
        return ($this->draft_flag) ? '下書き' : '';
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getPatientStatusLabelAttribute()
    {
        return config('const.patient_status.' . $this->patient_status . '.label');
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getTimingLabelAttribute()
    {
        $unitLabel = ($this->timing_unit === 'month') ? 'ヶ月' : '年';
        $time = new Carbon($this->time);

        return $this->timing_number . $unitLabel . '後の' . $time->hour . '時';
    }

}
