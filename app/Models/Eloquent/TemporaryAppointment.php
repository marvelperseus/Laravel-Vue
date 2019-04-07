<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TemporaryAppointment extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['time_label', 'unit_name', 'treatment', 'past_day'];

    /**
     * The attributes to Cast to Native Type.
     *
     * @var array
     */
    protected $casts = ['form_content' => 'array'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * get Appointment Start and End Label.
     *
     * @return array
     */
    public function getTimeLabelAttribute()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->format('Y-m-d H:i');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $this->end)->format('H:i');

        return $start . '〜' . $end;
    }

    /**
     * get Treatment Name.
     *
     * @return array
     */
    public function getUnitNameAttribute()
    {
        $appointmentTableSetting = AppointmentTableSetting::find($this->clinic_id);
        $unitNames = explode(',', $appointmentTableSetting->unit_names);

        return $unitNames[$this->unit];
    }

    /**
     * get Treatment Name.
     *
     * @return array
     */
    public function getTreatmentAttribute()
    {
        $treatment = Treatment::find($this->treatment_id);
        return $treatment->treatment;
    }

    /**
     * get Treatment Name.
     *
     * @return array
     */
    public function getPastDayAttribute()
    {
        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $createdAt->diffInDays(Carbon::now());
    }
}
