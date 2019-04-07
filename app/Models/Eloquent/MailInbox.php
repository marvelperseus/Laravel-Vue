<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class MailInbox extends Model
{

    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['type_label'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getTypeLabelAttribute()
    {
        return config('const.mail_inbox_type.' . $this->type . '.label');
    }

}
