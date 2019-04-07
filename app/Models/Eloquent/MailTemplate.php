<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

}
