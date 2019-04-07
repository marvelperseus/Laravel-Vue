<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\AppointmentTableSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentTableSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        return $user->clinic->appointmentTableSetting;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\AppointmentTableSetting $appointmentTableSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(AppointmentTableSetting $appointmentTableSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'display_unit_number' => ['required', 'integer', 'between:1,8'],
        ]);

        $user = Auth::user();
        $model = null;
        if ($user->clinic->appointmentTableSetting->exists) {
            // Update
            $model = $user->clinic->appointmentTableSetting;
        } else {
            // Create
            $model = new AppointmentTableSetting();
            $model->clinic_id = $user->clinic->id;
        }

        // 列名成型
        $data = array();
        foreach ($request->unit_names_array as $item) {
            if (!empty($item['value'])) {
                $data[] = $item['value'];
            }
        }
        $model->unit_names = implode(',', $data);

        $model->display_unit_number = $request->display_unit_number;
        $model->time_delimiter = $request->time_delimiter;
        $model->display_items = implode(',', array_filter($request->display_items_array));

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\AppointmentTableSetting $appointmentTableSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppointmentTableSetting $appointmentTableSetting)
    {
        //
    }
}
