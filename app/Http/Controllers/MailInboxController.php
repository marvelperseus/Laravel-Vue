<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\MailInbox;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MailInboxController extends Controller
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
        return $user->clinic->mailInboxes;
    }

    /**
     * Display a count of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function counter()
    {
        $user = Auth::user();

        $count = DB::table('mail_inboxes')
            ->select(
                DB::raw("count(type = 'new' or null) as count_new"),
                DB::raw("count(type = 'change' or null) as count_change"),
                DB::raw("count(type = 'cancel' or null) as count_cancel"),
                DB::raw("count(type = 'error' or null) as count_error"),
                DB::raw("count(id) as count_total")
            )
            ->where('clinic_id', $user->clinic->id)
            ->whereNull('read_at')
            ->first();

        return (array)$count;
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
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function show(MailInbox $mailInbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function edit(MailInbox $mailInbox)
    {
        //
    }

    /**
     * Read the specified resource in storage.
     *
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function read(MailInbox $mailInbox)
    {
        $mailInbox->read_at = Carbon::now();
        $mailInbox->save();

        return response()->json();
    }

    /**
     * Un Read the specified resource in storage.
     *
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function unread(MailInbox $mailInbox)
    {
        $mailInbox->read_at = null;
        $mailInbox->save();

        return response()->json();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailInbox $mailInbox)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\MailInbox $mailInbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailInbox $mailInbox)
    {
        $mailInbox->delete();
        return response()->json();
    }

    /**
     * Selected Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function selectedDelete(Request $request)
    {
        MailInbox::destroy($request->ids);
        return response()->json();
    }

    /**
     * Selected Read the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function selectedRead(Request $request)
    {
        DB::table('mail_inboxes')
            ->whereIn('id', $request->ids)
            ->update(['read_at' => Carbon::now()]);

        return response()->json();
    }

    /**
     * Selected Un Read the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function selectedUnread(Request $request)
    {
        DB::table('mail_inboxes')
            ->whereIn('id', $request->ids)
            ->update(['read_at' => null]);

        return response()->json();
    }

}
