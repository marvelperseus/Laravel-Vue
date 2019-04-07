<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ReminderMaster extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['draft_flag_label', 'timing_label'];

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
    public function getTimingLabelAttribute()
    {
        $time = new Carbon($this->time);
        if ($this->timing === 0) {
            return '当日' . $time->hour . '時間前';
        } else {
            return $this->timing . '日前の' . $time->hour . '時';
        }
    }

}
