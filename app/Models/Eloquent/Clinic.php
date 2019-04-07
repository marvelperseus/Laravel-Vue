<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function treatments()
    {
        return $this->hasMany('App\Models\Eloquent\Treatment');
    }

    public function patients()
    {
        return $this->hasMany('App\Models\Eloquent\Patient');
    }

    public function appointmentTableSetting()
    {
        return $this->hasOne('App\Models\Eloquent\AppointmentTableSetting')->withDefault();
    }

    public function webAppointmentSetting()
    {
        return $this->hasOne('App\Models\Eloquent\WebAppointmentSetting')->withDefault();
    }

    public function webAppointmentTreatments()
    {
        return $this->hasMany('App\Models\Eloquent\WebAppointmentTreatment');
    }

    public function mailTemplates()
    {
        return $this->hasMany('App\Models\Eloquent\MailTemplate');
    }

    public function reminderMasters()
    {
        return $this->hasMany('App\Models\Eloquent\ReminderMaster');
    }

    public function recallMasters()
    {
        return $this->hasMany('App\Models\Eloquent\RecallMaster');
    }

    public function webAppointmentMails()
    {
        return $this->hasMany('App\Models\Eloquent\WebAppointmentMail');
    }

    public function webAppointmentPage()
    {
        return $this->hasOne('App\Models\Eloquent\WebAppointmentPage')->withDefault();
    }

    public function webAppointmentByPatientSettings()
    {
        return $this->hasMany('App\Models\Eloquent\WebAppointmentByPatientSetting');
    }

    public function clinicTime()
    {
        return $this->hasOne('App\Models\Eloquent\ClinicTime')->withDefault();
    }

    public function clinicWebTime()
    {
        return $this->hasOne('App\Models\Eloquent\ClinicWebTime')->withDefault();
    }

    public function clinicLunchbreak()
    {
        return $this->hasOne('App\Models\Eloquent\ClinicLunchbreak')->withDefault();
    }

    public function clinicHolidays()
    {
        return $this->hasMany('App\Models\Eloquent\ClinicHoliday');
    }

    public function clinicWebHolidays()
    {
        return $this->hasMany('App\Models\Eloquent\ClinicWebHoliday');
    }

    public function mailInboxes()
    {
        return $this->hasMany('App\Models\Eloquent\MailInbox');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Eloquent\Appointment');
    }

    public function temporaryAppointments()
    {
        return $this->hasMany('App\Models\Eloquent\TemporaryAppointment');
    }

}
