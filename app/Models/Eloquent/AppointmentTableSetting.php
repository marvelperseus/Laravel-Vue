<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class AppointmentTableSetting extends Model
{
    /**
     * The attributes that Accessor to add to the arrays.
     *
     * @var array
     */
    protected $appends = ['unit_names_array', 'display_items_array'];

    public function clinic()
    {
        return $this->belongsTo('App\Models\Eloquent\Clinic');
    }

    /**
     * Get Role Label Name.
     *
     * @return array
     */
    public function getUnitNamesArrayAttribute()
    {
        $unitSize = 8;
        $data = array_pad(explode(',', $this->unit_names), $unitSize, '');

        $names = array();
        for ($i = 1; $i <= $unitSize; $i++) {
            $item = array(
                'name' => 'unit_name_' . $i,
                'label' => '列名' . $i,
                'value' => $data[$i-1]
            );
            $names[] = $item;
        }

        return $names;
    }

    /**
     * Get Role Label Name.
     *
     * @return string
     */
    public function getDisplayItemsArrayAttribute()
    {
        return explode(',', $this->display_items);
    }
}
