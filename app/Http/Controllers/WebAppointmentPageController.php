<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\WebAppointmentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppointmentPageController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
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

        $webAppointmentPage = $user->clinic->webAppointmentPage;
        // フォーム群デフォルト設定
        if (empty($webAppointmentPage) || empty($webAppointmentPage->form_objects)) {
            $webAppointmentPage->form_objects = array(
                'patient_name',
                'patient_tel',
                'patient_email',
                'patient_birthday'
            );
        }

        return $webAppointmentPage;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentPage  $webAppointmentPage
     * @return \Illuminate\Http\Response
     */
    public function edit(WebAppointmentPage $webAppointmentPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'thankspage_url' => ['nullable', 'url'],
        ]);

        $user = Auth::user();
        $model = null;
        if ($user->clinic->webAppointmentPage->exists) {
            // Update
            $model = $user->clinic->webAppointmentPage;
        } else {
            // Create
            $model = new WebAppointmentPage();
            $model->clinic_id = $user->clinic->id;
        }

        $model->special_note = $request->special_note;
        $model->cancel_message = $request->cancel_message;
        $model->end_message = $request->end_message;
        $model->form_objects = $request->form_objects;
        $model->thankspage_url = $request->thankspage_url;

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\WebAppointmentPage  $webAppointmentPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebAppointmentPage $webAppointmentPage)
    {
        //
    }
}
