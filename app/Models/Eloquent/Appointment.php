<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Appointment extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    public function cancel() 
    {
        return $this->hasOne()
    }


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

}
