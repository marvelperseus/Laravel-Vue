<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\SaveAppointmentRequest;
use App\Models\Eloquent\Appointment;
use App\Models\Eloquent\Clinic;
use App\Models\Eloquent\ClinicWebHoliday;
use App\Models\Eloquent\MailInbox;
use App\Models\Eloquent\Patient;
use App\Models\Eloquent\TemporaryAppointment;
use App\Notifications\WebAppointmentNotify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{

    /**
     * Show the Login Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function login(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.login', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Show the List Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function list(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.list', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Show the Profile Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function profile(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.profile', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Show the Update form Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function updateform(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.profile', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }


    /**
     * Show the Index Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function index(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.index', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Show the Steps Page.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function steps(Request $request, string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
	return view('web.appointment.steps', [
		'clinicName' => $clinic->name, 
		'appKey' => $app_key, 
		'appStatus' => $clinic->webAppointmentSetting->status, 
		'login' => $request->exists('login'),
	]);
    }

    /**
     * Show the Terms Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function terms(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.terms', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Show the privacy Policy Page.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function privacyPolicy(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        return view('web.appointment.privacypolicy', ['clinicName' => $clinic->name, 'appKey' => $app_key, 'appStatus' => $clinic->webAppointmentSetting->status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SaveAppointmentRequest $request
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function store(SaveAppointmentRequest $request, string $app_key)
    {

        DB::beginTransaction();
        try {
            $clinic = $this->generateClinic($app_key);

            // 患者登録
            $patient = Patient::where('email', $request->patient_email)
                ->where('birthday', $request->patient_birthday)
                ->first();

            if (empty($patient)) {
                $patient = new Patient();
                $patient->status = config('const.patient_status.new.value');
                $patient->clinic_id = $clinic->id;
            }

            $patient->name = $request->patient_name;
            $patient->tel = $request->patient_tel;
            $patient->email = $request->patient_email;
            $patient->birthday = $request->patient_birthday;

            if (!empty($request->patient_name_kana)) {
                $patient->kana_name = $request->patient_name_kana;
            }
            if (!empty($request->patient_gender)) {
                $patient->gender = $request->patient_gender;
            }
            if (!empty($request->patient_zip)) {
                $patient->zip = $request->patient_zip;
            }
            if (!empty($request->patient_address)) {
                $patient->address = $request->patient_address;
            }

            $patient->save();


            // 予約登録
            $appointment = new Appointment();
            $appointment->start = $request->start;
//        $appointment->end = $request->end; *fullcalendar 予約のendは（診療内容の時間ではなく）時間区切りに合わせる
            $appointment->end = Carbon::createFromFormat('Y-m-d H:i', $request->start)->addMinutes($request->treatment_time)->toDateTimeString();
            $appointment->patient_id = $patient->id;
            $appointment->treatment_id = $request->treatment_id;
            $appointment->unit = $request->unit;
            $appointment->way = config('const.appointment_way.web.value');
            $appointment->note = $request->note;
            $appointment->clinic_id = $clinic->id;


            $appointment->save();


            // 受信メール登録 todo: データベース通知（Laravel）に以降したい
            $mailType = 'confirm';
            $mailContent = $clinic->webAppointmentMails->firstWhere('type', $mailType);

            $mailSubject = $mailContent->title;
            $mailBody = $mailContent->body;

            $mailBody = str_replace('%患者名%', $request->patient_name, $mailBody);

            $mailContent = '予約日時: ' . $request->time_label . "\n"
                . '診療メニュー: ' . $request->treatment . "\n"
                . '診療時間: ' . $request->treatment_time . "分\n"
                . 'お名前: ' . $request->patient_name . "\n"
                . '電話番号: ' . $request->patient_tel . "\n"
                . 'E-mail: ' . $request->patient_email . "\n"
                . '生年月日: ' . $request->patient_birthday . "\n\n"
                . "診療確認: \n" . url('/web/appointment/' . $app_key . '/list') . "\n";

            $mailBody = str_replace('%コンテンツ%', $mailContent, $mailBody);

            $mailInbox = new MailInbox();
            $mailInbox->type = config('const.mail_inbox_type.new.value');
            $mailInbox->name = $request->patient_name;
            $mailInbox->email = $request->patient_email;
            $mailInbox->body = $mailBody;
            $mailInbox->clinic_id = $clinic->id;

            $mailInbox->save();


            // 通知 todo: とりあえずメールのみ
            $patient->notify(new WebAppointmentNotify($mailSubject, $mailBody));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }


        return response()->json();
    }

    /**
     * Store a newly created temporary resource in storage.
     *
     * @param  \App\Http\Requests\SaveAppointmentRequest $request
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function storeTemporary(SaveAppointmentRequest $request, string $app_key)
    {
        $clinic = $this->generateClinic($app_key);

        $temporaryAppointments = new TemporaryAppointment();
        $temporaryAppointments->start = $request->start;
        $temporaryAppointments->end = Carbon::createFromFormat('Y-m-d H:i', $request->start)->addMinutes($request->treatment_time)->toDateTimeString();
        $temporaryAppointments->unit = $request->unit;
        $temporaryAppointments->treatment_id = $request->treatment_id;
        $temporaryAppointments->way = config('const.appointment_way.web.value');
        $temporaryAppointments->note = $request->note;

        $formContent = array(
            'name' => $request->patient_name,
            'tel' => $request->patient_tel,
            'email' => $request->patient_email,
            'birthday' => $request->patient_birthday
        );

        if (!empty($request->patient_name_kana)) {
            $formContent['kana_name'] = $request->patient_name_kana;
        }
        if (!empty($request->patient_gender)) {
            $formContent['gender'] = $request->patient_gender;
        }
        if (!empty($request->patient_zip)) {
            $formContent['zip'] = $request->patient_zip;
        }
        if (!empty($request->patient_address)) {
            $formContent['address'] = $request->patient_address;
        }

        $temporaryAppointments->form_content = $formContent;

        $clinic->temporaryAppointments()->save($temporaryAppointments);


        // 仮予約は患者登録はしない。擬似モデルで通知
        $patient = new Patient();
        $patient->email = $request->patient_email;

        // todo: store関数に同じ記述ある
        $mailType = 'temporary';
        $mailContent = $clinic->webAppointmentMails->firstWhere('type', $mailType);

        $mailSubject = $mailContent->title;
        $mailBody = $mailContent->body;

        $mailBody = str_replace('%患者名%', $request->patient_name, $mailBody);

        $mailContent = '予約日時: ' . $request->time_label . "\n"
            . '診療メニュー: ' . $request->treatment . "\n"
            . '診療時間: ' . $request->treatment_time . "分\n"
            . 'お名前: ' . $request->patient_name . "\n"
            . '電話番号: ' . $request->patient_tel . "\n"
            . 'E-mail: ' . $request->patient_email . "\n"
            . '生年月日: ' . $request->patient_birthday . "\n";

        $mailBody = str_replace('%コンテンツ%', $mailContent, $mailBody);

        $patient->notify(new WebAppointmentNotify($mailSubject, $mailBody));


        return response()->json();
    }

    public function cancel(Request $request, string $app_key) 
    {
        return response()->json();    
    }

    /**
     * Get the specified resource.
     *
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function clinic(string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        // 追加情報
        $options = array(
            // 予約TOPページURL
            'top_page_url' => '/web/appointment/' . $app_key,
            // 利用規約ページURL
            'term_page_url' => '/web/appointment/' . $app_key . '/terms',
            // プライバシーポリシーページURL
            'privacy_policy_page_url' => '/web/appointment/' . $app_key . '/privacy-policy'
        );

        // 基本設定（受付対象患者）
        $target = $clinic->webAppointmentSetting->target;
        $options['web_appointment_setting_target_title'] = '';
        if (!empty($target) && count($target) > 0) {
            foreach ($target as $item) {
                if ($item == 'first') {
                    $options['web_appointment_setting_targets'][] = array(
                        'href' => '/web/appointment/' . $app_key . '/steps',
                        'label' => '新規の方'
                    );
                } elseif ($item == 'treatment') {
                    $options['web_appointment_setting_targets'][] = array(
                        'href' => '/web/appointment/' . $app_key . '/steps?login',
                        'label' => '治療中の方'
                    );
                }
            }

            if (count($options['web_appointment_setting_targets']) == 2) {
                $options['web_appointment_setting_target_title'] = '新規（初診）または治療中をお選びください';
            }
        }

        // ページ設定
        $options['web_appointment_page'] = $clinic->webAppointmentPage;

        // 診療内容
        $options['treatments'] = DB::table('web_appointment_treatments')
            ->join('treatments', 'web_appointment_treatments.treatment_id', '=', 'treatments.id')
            ->select(
                'web_appointment_treatments.treatment_id as id',
                'treatments.treatment',
                'treatments.time'
            )
            ->where('web_appointment_treatments.clinic_id', '=', $clinic->id)
            ->get()->toArray();


        return array_merge($clinic->toArray(), $options);
    }

    /**
     * Get the appointments of patient
     * @param  $patient_id
     * @return array $appointments
     */
    public function appointments($patient_id) {
        $appointments = DB::table('appointments')
            ->join('treatments', 'appointments.treatment_id', '=', 'treatments.id')
            ->select(
                'appointments.unit as resourceId',
                'appointments.start',
                'appointments.end',
                'treatments.treatment as treatment'
            )
            ->where('appointments.patient_id', '=', $patient_id)
            ->get()->toArray();
        return $appointments;
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $app_key
     * @return \Illuminate\Http\Response
     */
    public function calendar(Request $request, string $app_key)
    {
        $clinic = $this->generateClinic($app_key);
        $webAppointmentSetting = $clinic->webAppointmentSetting;

        $responseData = array();

        // 予約を受け付ける日付の範囲指定
        $now = Carbon::now();
        $rangeStartIns = $now->addDays($webAppointmentSetting->start_day);
        $rangeEndIns = $now->copy()->addMonths($webAppointmentSetting->term);

        // 表示用
        $rangeStartDummy = $rangeStartIns->copy()->firstOfMonth();
        $rangeEndDummy = $rangeEndIns->copy()->endOfMonth();

        $responseData['valid_range'] = array(
            'start' => $rangeStartDummy->toDateString(),
            'end' => $rangeEndDummy->copy()->addDay()->toDateString()
        );

        // 時間区切りが選択した診療内容の時間を超える場合は、診療内容の時間を優先
        $timeDelimiter = $webAppointmentSetting->time_delimiter;
        if ($timeDelimiter > $request->treatment_time) {
            $timeDelimiter = $request->treatment_time;
        }
        $responseData['slot_duration'] = Carbon::createFromTime(0, $timeDelimiter, 0)->toTimeString();

        $minTime = $clinic->clinicWebTime->base['start'];
        $maxTime = $clinic->clinicWebTime->base['end'];

        $times = $clinic->clinicWebTime->times;
        if (count($times) > 0) {
            // 始業時間
            $min = array_column($clinic->clinicWebTime->times, 'start');
            $min[] = $minTime;
            $minTime = min($min);
            // 終業時間
            $max = array_column($clinic->clinicWebTime->times, 'end');
            $max[] = $maxTime;
            $maxTime = max($max);
        }

        $minTimeIns = Carbon::createFromTimeString($minTime)->minute(0);
        $maxTimeIns = Carbon::createFromTimeString($maxTime);

        $responseData['min_time'] = $minTimeIns->format('H:i:00');
        $responseData['max_time'] = $maxTimeIns->format('H:i:00');


        /** 予約空き状況データ成型 */
        // 予約対象の日数
        $dayCount = $rangeStartIns->diffInDays($rangeEndIns);
        // 休診日（全休）
        $fullHolidays = array_column(
            ClinicWebHoliday::where('clinic_id', $clinic->id)
                ->where('type', config('const.holiday.full.key'))
                ->get()->toArray(),
            'start'
        );

        // 休診日（午前休）
        $amHolidays = array_column(
            ClinicWebHoliday::where('clinic_id', $clinic->id)
                ->where('type', config('const.holiday.am.key'))
                ->get()->toArray(),
            'start'
        );
        // 休診日（午後休）
        $pmHolidays = array_column(
            ClinicWebHoliday::where('clinic_id', $clinic->id)
                ->where('type', config('const.holiday.pm.key'))
                ->get()->toArray(),
            'start'
        );

        // 予約データ
        $rawAppointments = DB::table('appointments')
            ->select('start', 'end', 'patient_id', 'treatment_id', 'unit')
            ->where('clinic_id', '=', $clinic->id)
            ->whereIn('unit', $webAppointmentSetting->units)
            ->where('start', '>=', $rangeStartIns->toDateString())
            ->where('start', '<=', $rangeEndIns->toDateString())
            ->get();

        // 予約データを日付単位で格納
        $appointments = array();
        foreach ($rawAppointments as $data) {
            $ins = Carbon::createFromFormat('Y-m-d H:i:s', $data->start);
            $appointments[$ins->toDateString()][] = (array)$data;
        }

        $availabilityMonth = array();
        $availabilityAgenda = array();

        $currentMinTime = $clinic->clinicWebTime->base['start'];
        $currentMaxTime = $clinic->clinicWebTime->base['end'];
        $times = $clinic->clinicWebTime->times;
        $lunchbreaks = $clinic->clinicLunchbreak;
        $lunchTimes = $lunchbreaks->times;

        for ($i = 0; $i < $dayCount; $i++) {
            $date = $rangeStartIns->copy()->addDays($i);

            if (!in_array($date->toDateString(), $fullHolidays)) {
                // 曜日毎の診療時間を取得
                $week = strtolower($date->format('D'));

                if (count($times) > 0 && array_key_exists($week, $times)) {
                    if (!empty($times[$week]['start']) && !empty($times[$week]['end'])) {
                        $currentMinTime = $times[$week]['start'];
                        $currentMaxTime = $times[$week]['end'];
                    }
                }
                // 診療時間調整（午前休の場合）
                if (in_array($date->toDateString(), $amHolidays)) {
                    $currentMinTime = $lunchbreaks->base['end'];
                    if (count($lunchTimes) > 0 && array_key_exists($week, $lunchTimes) && !empty($lunchTimes[$week]['end'])) {
                        $currentMinTime = $lunchTimes[$week]['end'];
                    }
                }
                // 診療時間調整（午後休の場合）
                if (in_array($date->toDateString(), $pmHolidays)) {
                    $currentMaxTime = $lunchbreaks->base['start'];
                    if (count($lunchTimes) > 0 && array_key_exists($week, $lunchTimes) && !empty($lunchTimes[$week]['start'])) {
                        $currentMaxTime = $lunchTimes[$week]['start'];
                    }
                }

                // 予約 可／不可
                $isAvailable = false;

                $currentMinTimeIns = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $currentMinTime);
                $currentMaxTimeIns = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $currentMaxTime)->subMinutes($request->treatment_time);

                // 昼休み時間
                $lunchbreakStart = $lunchbreaks->base['start'];
                $lunchbreakEnd = $lunchbreaks->base['end'];
                if (count($lunchTimes) > 0 && array_key_exists($week, $lunchTimes) && !empty($lunchTimes[$week]['start'])) {
                    $lunchbreakStart = $lunchTimes[$week]['start'];
                }
                if (count($lunchTimes) > 0 && array_key_exists($week, $lunchTimes) && !empty($lunchTimes[$week]['end'])) {
                    $lunchbreakEnd = $lunchTimes[$week]['end'];
                }

                // 時間帯イベント生成
                for ($time = $currentMinTimeIns;
                     $time->lte($currentMaxTimeIns);
                     $time->addMinutes($timeDelimiter)) {

                    $currentEnd = $time->copy()->addMinutes($request->treatment_time);
                    // *fullcalendar 予約のendは（診療内容の時間ではなく）時間区切りに合わせる
                    $dummyEnd = $time->copy()->addMinutes($timeDelimiter);
                    $stock = $webAppointmentSetting->units;

                    if ($lunchbreakStart < $currentEnd->format('H:i') && $time->format('H:i') < $lunchbreakEnd) {
                        // 昼休みは除外
                        $availabilityAgenda[] = array(
                            'title' => '-',
                            'start' => $time->toDateTimeString(),
                            'end' => $dummyEnd->toDateTimeString(),
                            'backgroundColor' => '#fff',
                            'borderColor' => '#fff',
                            'textColor' => '#676D77'
                        );
                    } elseif (array_key_exists($time->toDateString(), $appointments)) {
                        // 予約データと比較
                        // 予約の残数検索
                        $reserved = array();
                        foreach ($appointments[$time->toDateString()] as $appointment) {
                            // 予約あり
                            if (Carbon::createFromFormat('Y-m-d H:i:s', $appointment['start'])->lt($currentEnd) &&
                                $time->lt(Carbon::createFromFormat('Y-m-d H:i:s', $appointment['end']))) {
                                // 予約済みのユニットを保持
                                $reserved[] = $appointment['unit'];
                            }
                        }

                        if (count($reserved) >= count($webAppointmentSetting->units)) {
                            // 残数なし
                            $availabilityAgenda[] = array(
                                'title' => '-',
                                'start' => $time->toDateTimeString(),
                                'end' => $dummyEnd->toDateTimeString(),
                                'backgroundColor' => '#fff',
                                'borderColor' => '#fff',
                                'textColor' => '#676D77'
                            );
                        } else {
                            if (count($reserved) > 0) {
                                $stock = array_values(array_diff($stock, $reserved));
                            }

                            $availabilityAgenda[] = array(
                                'title' => '〇',
                                'start' => $time->toDateTimeString(),
                                'end' => $dummyEnd->toDateTimeString(),
                                'real_end' => $currentEnd->format('H:i'),
                                'unit' => $stock[0],
                                'backgroundColor' => '#fff',
                                'borderColor' => '#fff',
                                'textColor' => '#49A2BC'
                            );
                            $isAvailable = true;
                        }
                    } else {
                        $availabilityAgenda[] = array(
                            'title' => '〇',
                            'start' => $time->toDateTimeString(),
                            'end' => $dummyEnd->toDateTimeString(),
                            'real_end' => $currentEnd->format('H:i'),
                            'unit' => $stock[0],
                            'backgroundColor' => '#fff',
                            'borderColor' => '#fff',
                            'textColor' => '#49A2BC'
                        );
                        $isAvailable = true;
                    }
                }

                // 月表示用
                if ($isAvailable) {
                    $availabilityMonth[] = array(
                        'title' => '〇',
                        'start' => $date->toDateString(),
                        'editable' => false,
                        'backgroundColor' => '#fff',
                        'borderColor' => '#fff',
                        'textColor' => '#49A2BC'
                    );
                } else {
                    $availabilityMonth[] = array(
                        'title' => '-',
                        'start' => $date->toDateString(),
                        'editable' => false,
                        'backgroundColor' => '#fff',
                        'borderColor' => '#fff',
                        'textColor' => '#676D77'
                    );
                }
            } else {
                // 休診日（全休）
                $availabilityMonth[] = array(
                    'title' => '-',
                    'start' => $date->toDateString(),
                    'editable' => false,
                    'backgroundColor' => '#fff',
                    'borderColor' => '#fff',
                    'textColor' => '#676D77'
                );
            }
        }


        // 予約開始月の日埋め
        for ($day = $rangeStartDummy;
             $day->lt($rangeStartIns->copy()->subDay());
             $day->addDay()) {

            $availabilityMonth[] = array(
                'title' => '-',
                'start' => $day->toDateString(),
                'editable' => false,
                'backgroundColor' => '#fff',
                'borderColor' => '#fff',
                'textColor' => '#676D77'
            );
        }

        // 予約終了月の日埋め
        for ($day = $rangeEndIns->copy();
             $day->lte($rangeEndDummy);
             $day->addDay()) {

            $availabilityMonth[] = array(
                'title' => '-',
                'start' => $day->toDateString(),
                'editable' => false,
                'backgroundColor' => '#fff',
                'borderColor' => '#fff',
                'textColor' => '#676D77'
            );
        }


        $responseData['availability_month'] = $availabilityMonth;
        $responseData['availability_agenda'] = $availabilityAgenda;

        $responseData['auto_regist'] = $webAppointmentSetting->auto_regist;


        return $responseData;
    }

    /**
     * Generate Clinic Model.
     *
     * @param string $app_key
     * @return App\Models\Eloquent\Clinic
     */
    private function generateClinic(string $app_key)
    {
        $clinic = Clinic::where('app_key', $app_key)->first();
        return $clinic;
    }

}
