<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\WebAppointmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppointmentMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return $user->clinic->webAppointmentMails;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentMail $webAppointmentMail
     * @return \Illuminate\Http\Response
     */
    public function edit(WebAppointmentMail $webAppointmentMail)
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
            'title' => ['nullable', 'string', 'max:191'],
            'body' => ['required', 'string']
        ]);

        $user = Auth::user();
        $item = $user->clinic->webAppointmentMails->firstWhere('type', $request->type);
        $model = null;
        if (empty($item)) {
            $model = new WebAppointmentMail();
            $model->clinic_id = $user->clinic->id;
        } else {
            $model = $item;
        }

        $model->type = $request->type;
        $model->title = $request->title;
        $model->body = $request->body;

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\WebAppointmentMail $webAppointmentMail
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebAppointmentMail $webAppointmentMail)
    {
        //
    }
}
