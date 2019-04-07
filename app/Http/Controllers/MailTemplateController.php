<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailTemplateController extends Controller
{

    /**
     * The attributes that Validation Rules.
     *
     * @var array
     */
    private $validationRules = [
        'name' => ['required', 'string', 'max:191'],
        'title' => ['nullable', 'string', 'max:191'],
        'body' => ['required', 'string'],
    ];

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
        return $user->clinic->mailTemplates;
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
        $user = Auth::user();

        $request->validate($this->validationRules);

        $mailTemplate = new MailTemplate();
        $mailTemplate->name = $request->name;
        $mailTemplate->title = $request->title;
        $mailTemplate->body = $request->body;
        $mailTemplate->clinic_id = $user->clinic->id;

        $mailTemplate->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(MailTemplate $mailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(MailTemplate $mailTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eloquent\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $request->validate($this->validationRules);

        $mailTemplate->name = $request->name;
        $mailTemplate->title = $request->title;
        $mailTemplate->body = $request->body;

        $mailTemplate->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailTemplate $mailTemplate)
    {
        $mailTemplate->delete();
        return response()->json();
    }
}
