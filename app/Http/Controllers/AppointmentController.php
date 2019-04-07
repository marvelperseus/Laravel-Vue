<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
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
        $user = Auth::user();

        $data = array();
        // Agenda 時間区切り
        $timeDelemiter = $user->clinic->appointmentTableSetting->time_delimiter;
        // デフォルト
        if (empty($timeDelemiter)) {
            $timeDelemiter = 30;
        }
        $data['slotDuration'] = Carbon::createFromTime(0, $timeDelemiter, 0)->toTimeString();

        // 昼休み
        $lunchbreaks = $user->clinic->clinicLunchbreak;
        $lunchbreaksTimes = $lunchbreaks->times;
        $data['holidaysForLunchbreak'] = array();
        $data['timesLunchbreak'] = array();

        // Business Hours（週）
        $data['businessHours'] = array();

        $baseStart = $user->clinic->clinicTime->base['start'];
        $baseEnd = $user->clinic->clinicTime->base['end'];

        // デフォルト
        if (empty($baseStart)) {
            $baseStart = '09:00';
        }
        if (empty($baseEnd)) {
            $baseEnd = '19:00';
        }

        $minTime = Carbon::createFromTimeString($baseStart);
        $maxTime = Carbon::createFromTimeString($baseEnd);

        $times = $user->clinic->clinicTime->times;
        if (!empty($times) && count($times) > 0) {
            $weekNumbers = array('sun' => 0, 'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6);
            foreach ($weekNumbers as $key => $value) {
                $time = $times[$key];
                if (!empty($time['start']) && !empty($time['end'])) {
                    $data['businessHours'][] = array(
                        'dow' => [$value],
                        'start' => $time['start'],
                        'end' => $time['end']
                    );

                    $minTime = $minTime->min(Carbon::createFromTimeString($time['start']));
                    $maxTime = $maxTime->max(Carbon::createFromTimeString($time['end']));
                } else {
                    $data['businessHours'][] = array(
                        'dow' => [$value],
                        'start' => $baseStart,
                        'end' => $baseEnd
                    );
                }

                // お昼休み
                $lunchtime = $lunchbreaksTimes[$key];
                if (!empty($lunchtime['start']) && !empty($lunchtime['end'])) {
                    $data['timesLunchbreak'][$value]['start'] = $lunchtime['start'];
                    $data['timesLunchbreak'][$value]['end'] = $lunchtime['end'];
                } else {
                    $data['timesLunchbreak'][$value]['start'] = $lunchbreaks->base['start'];
                    $data['timesLunchbreak'][$value]['end'] = $lunchbreaks->base['end'];
                }
            }
        } else {
            $data['businessHours'][] = array(
                'dow' => [0, 1, 2, 3, 4, 5, 6],
                'start' => $baseStart,
                'end' => $baseEnd
            );
        }

        // Min Time, Max Time
        $data['minTime'] = $minTime->subHours(2)->format('H:00:00');
        $data['maxTime'] = $maxTime->addHours(2)->format('H:00:00');

        // ユニット
        $unitNumber = $user->clinic->unit_number;
        // デフォルト
        if (empty($unitNumber)) {
            $unitNumber = 3;
        }

        $displayUnitNumber = $user->clinic->appointmentTableSetting->display_unit_number;
        $unitNames = explode(',', $user->clinic->appointmentTableSetting->unit_names);

        // デフォルト
        if (empty($displayUnitNumber)) {
            $displayUnitNumber = $unitNumber;
        }
        if (empty($user->clinic->appointmentTableSetting->unit_names)) {
            $unitNames = array();
        }

        $resources = array();
        if (count($unitNames) >= $displayUnitNumber) {
            $resources = array_slice($unitNames, 0, $displayUnitNumber);
        } else {
            $resources = $unitNames;
            for ($i = count($unitNames) + 1; $i <= $displayUnitNumber; $i++) {
                $resources[] = 'ユニット' . $i;
            }
        }

        $data['resources'] = array();
        for ($i = 0; $i < count($resources); $i++) {
            $resource = array(
                'id' => $i,
                'title' => $resources[$i]
            );
            if ($i >= $unitNumber) {
                $resource['businessHours'] = array(
                    'start' => '00:00',
                    'end' => '00:00',
                );
            }
            $data['resources'][] = $resource;
        }


        $events = array();

        // 休診日
        $holidays = $user->clinic->clinicHolidays;
        foreach ($holidays as $holiday) {
            // Month View
            $monthEvent = array();
            $monthEvent['title'] = config('const.holiday.' . $holiday->type . '.title');
            $monthEvent['start'] = $holiday->start;
            $monthEvent['color'] = config('const.holiday.' . $holiday->type . '.color');
            $monthEvent['editable'] = false;
            $events[] = $monthEvent;

            // Agenda View
            $agendaEvent = array();
            $start = new Carbon($holiday->start);

            // 午前休、午後休みの終了、開始時間の取得
            $amMax = $lunchbreaks->base['end'];
            $pmMin = $lunchbreaks->base['start'];
            if (count($lunchbreaks->times) > 0) {
                $week = strtolower($start->format('D'));
                if (!empty($lunchbreaks->times[$week]['start']) && !empty($lunchbreaks->times[$week]['end'])) {
                    $amMax = $lunchbreaks->times[$week]['end'];
                    $pmMin = $lunchbreaks->times[$week]['start'];
                }
            }

            switch ($holiday->type) {
                case config('const.holiday.full.key'):
                    $agendaEvent['start'] = $start->format('Y-m-d 00:00:00');
                    $agendaEvent['end'] = $start->format('Y-m-d 23:59:59');
                    break;
                case config('const.holiday.am.key'):
                    $agendaEvent['start'] = $start->format('Y-m-d 00:00:00');
                    $agendaEvent['end'] = $start->format('Y-m-d ' . $amMax . ':00');
                    break;
                case config('const.holiday.pm.key'):
                    $agendaEvent['start'] = $start->format('Y-m-d ' . $pmMin . ':00');
                    $agendaEvent['end'] = $start->format('Y-m-d 23:59:59');
                    break;
            }
            $agendaEvent['backgroundColor'] = config('const.holiday.' . $holiday->type . '.color');
            $agendaEvent['rendering'] = 'background';
            $events[] = $agendaEvent;

            $data['holidaysForLunchbreak'][] = $start->format('Y-m-d');
        }

        // 予約
        $appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('treatments', 'appointments.treatment_id', '=', 'treatments.id')
            ->select(
                'appointments.unit as resourceId',
                'patients.name as title',
                'appointments.start',
                'appointments.end',
                'treatments.color as backgroundColor',
                DB::raw("'#fff' as borderColor"),
                DB::raw("'#212121' as textColor")
            )
            ->where('appointments.clinic_id', '=', $user->clinic->id)
            ->get()->toArray();

        $data['appointments'] = array_merge($events, $appointments);


        return $data;
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
     * @param  \App\Models\Eloquent\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Eloquent\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
