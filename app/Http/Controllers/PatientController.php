<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{

    /**
     * The attributes that Validation Rules.
     *
     * @var array
     */
    private $validationRules = [
        'karte_number' => ['nullable', 'string', 'max:191'],
        'insurance_number' => ['nullable', 'string', 'max:191'],
        'name' => ['required', 'string', 'max:191'],
        'kana_name' => ['nullable', 'string', 'max:191'],
        'zip' => ['nullable', 'string', 'max:191'],
        'tel' => ['nullable', 'string', 'max:191'],
        'email' => ['nullable', 'string', 'email', 'max:191'],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return $user->clinic->patients;
    }

    /**
     * Search a listing of the resource.
     *
     * @param  Request $request
     * @return $patient
     */
    public function login(Request $request)
    {
        $patient = Patient::where('email', $request->email)
            ->where('birthday', $request->birthday)
            ->first();

        return $patient;
    }

    /**
     * Search a listing of the resource.
     *
     * @param  string $keyword
     * @return $res
     */
    public function search(string $keyword)
    {
        $user = Auth::user();

        $keyword = '%'.$keyword.'%';
        $res = DB::table('patients')
            ->where('clinic_id', '=', $user->clinic->id)
            ->where('name', 'like', $keyword)
            ->orWhere('karte_number', 'like', $keyword)
            ->orWhere('kana_name', 'like', $keyword)
            ->orWhere('tel', 'like', $keyword)
            ->orWhere('email', 'like', $keyword)
            ->take(10)
            ->get();

        return $res;
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
        $request->validate($this->validationRules);

        $user = Auth::user();

        $patient = new Patient();
        $patient->status = $request->status;
        $patient->karte_number = $request->karte_number;
        $patient->insurance_number = $request->insurance_number;
        $patient->name = $request->name;
        $patient->kana_name = $request->kana_name;
        $patient->gender = $request->gender;
        $patient->birthday = $request->birthday;
        $patient->zip = $request->zip;
        $patient->address = $request->address;
        $patient->contact_way = $request->contact_way;
        $patient->tel = $request->tel;
        $patient->email = $request->email;
        $patient->note = $request->note;
        $patient->clinic_id = $user->clinic->id;

        $patient->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return $patient;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Eloquent\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate($this->validationRules);

        $patient->status = $request->status;
        $patient->karte_number = $request->karte_number;
        $patient->insurance_number = $request->insurance_number;
        $patient->name = $request->name;
        $patient->kana_name = $request->kana_name;
        $patient->gender = $request->gender;
        $patient->birthday = $request->birthday;
        $patient->zip = $request->zip;
        $patient->address = $request->address;
        $patient->contact_way = $request->contact_way;
        $patient->tel = $request->tel;
        $patient->email = $request->email;
        $patient->note = $request->note;

        $patient->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json();
    }
}
