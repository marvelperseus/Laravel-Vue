<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Stateful API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Application for User.
Route::post('/logout', 'Auth\LoginController@logout');

Route::get('/account', 'AccountController@show');

Route::get('/clinic', 'ClinicController@show');
Route::post('/clinic', 'ClinicController@update');

Route::get('/appointment-table-settings', 'AppointmentTableSettingController@show');
Route::post('/appointment-table-settings', 'AppointmentTableSettingController@update');

Route::get('/treatments', 'TreatmentController@index');
Route::post('/treatments', 'TreatmentController@store');
Route::post('/treatments/{treatment}', 'TreatmentController@update');
//Route::delete('/treatments/{treatment}', 'TreatmentController@destroy');
Route::get('/treatments/delete/{treatment}', 'TreatmentController@destroy');

Route::get('/patients', 'PatientController@index');
Route::post('/patients', 'PatientController@store');
Route::post('/patients/login', 'PatientController@login');
Route::get('/patients/{patient}', 'PatientController@show');
Route::post('/patients/{patient}', 'PatientController@update');
//Route::delete('/patients/{patient}', 'PatientController@destroy');
Route::get('/patients/delete/{patient}', 'PatientController@destroy');
Route::get('/patients/search/{keyword}', 'PatientController@search');

Route::get('/web-appointment-settings', 'WebAppointmentSettingController@show');
Route::post('/web-appointment-settings', 'WebAppointmentSettingController@update');

Route::get('/web-appointment-treatments', 'WebAppointmentTreatmentController@index');
Route::post('/web-appointment-treatments', 'WebAppointmentTreatmentController@store');
//Route::delete('/web-appointment-treatments/{webAppointmentTreatment}', 'WebAppointmentTreatmentController@destroy');
Route::get('/web-appointment-treatments/delete/{webAppointmentTreatment}', 'WebAppointmentTreatmentController@destroy');

Route::get('/mail-templates', 'MailTemplateController@index');
Route::post('/mail-templates', 'MailTemplateController@store');
Route::post('/mail-templates/{mailTemplate}', 'MailTemplateController@update');
//Route::delete('/mail-templates/{mailTemplate}', 'MailTemplateController@destroy');
Route::get('/mail-templates/delete/{mailTemplate}', 'MailTemplateController@destroy');

Route::get('/reminder-masters', 'ReminderMasterController@index');
Route::post('/reminder-masters', 'ReminderMasterController@store');
Route::post('/reminder-masters/{reminderMaster}', 'ReminderMasterController@update');
//Route::delete('/reminder-masters/{reminderMaster}', 'ReminderMasterController@destroy');
Route::get('/reminder-masters/delete/{reminderMaster}', 'ReminderMasterController@destroy');

Route::get('/recall-masters', 'RecallMasterController@index');
Route::post('/recall-masters', 'RecallMasterController@store');
Route::post('/recall-masters/{recallMaster}', 'RecallMasterController@update');
//Route::delete('/recall-masters/{recallMaster}', 'RecallMasterController@destroy');
Route::get('/recall-masters/delete/{recallMaster}', 'RecallMasterController@destroy');

Route::get('/web-appointment-mails', 'WebAppointmentMailController@index');
Route::post('/web-appointment-mails', 'WebAppointmentMailController@update');

Route::get('/web-appointment-pages', 'WebAppointmentPageController@show');
Route::post('/web-appointment-pages', 'WebAppointmentPageController@update');

Route::get('/web-appointment-by-patient_settings', 'WebAppointmentByPatientSettingController@index');
Route::get('/web-appointment-by-patient_settings/units', 'WebAppointmentByPatientSettingController@units');
Route::post('/web-appointment-by-patient_settings', 'WebAppointmentByPatientSettingController@store');
Route::post('/web-appointment-by-patient_settings/{webAppointmentByPatientSetting}', 'WebAppointmentByPatientSettingController@update');
//Route::delete('/web-appointment-by-patient_settings/{webAppointmentByPatientSetting}', 'WebAppointmentByPatientSettingController@destroy');
Route::get('/web-appointment-by-patient_settings/delete/{webAppointmentByPatientSetting}', 'WebAppointmentByPatientSettingController@destroy');

Route::get('/clinic-times', 'ClinicTimeController@show');
Route::post('/clinic-times', 'ClinicTimeController@update');

Route::get('/clinic-web-times', 'ClinicWebTimeController@show');
Route::post('/clinic-web-times', 'ClinicWebTimeController@update');

Route::get('/clinic-lunchbreaks', 'ClinicLunchbreakController@show');
Route::post('/clinic-lunchbreaks', 'ClinicLunchbreakController@update');

Route::get('/clinic-holidays', 'ClinicHolidayController@index');
Route::get('/clinic-holidays/mini', 'ClinicHolidayController@mini');
Route::post('/clinic-holidays', 'ClinicHolidayController@update');

Route::get('/clinic-web-holidays', 'ClinicWebHolidayController@index');
Route::post('/clinic-web-holidays', 'ClinicWebHolidayController@update');
Route::get('/clinic-web-holidays/copy', 'ClinicWebHolidayController@copy');

Route::get('/mail-inboxes', 'MailInboxController@index');
Route::post('/mail-inboxes/read/{mailInbox}', 'MailInboxController@read');
Route::post('/mail-inboxes/unread/{mailInbox}', 'MailInboxController@unread');
//Route::delete('/mail-inboxes/{mailInbox}', 'MailInboxController@destroy');
Route::get('/mail-inboxes/delete/{mailInbox}', 'MailInboxController@destroy');
Route::post('/mail-inboxes/selected/delete', 'MailInboxController@selectedDelete');
Route::post('/mail-inboxes/selected/read', 'MailInboxController@selectedRead');
Route::post('/mail-inboxes/selected/unread', 'MailInboxController@selectedUnread');
Route::get('/mail-inboxes/counter', 'MailInboxController@counter');

Route::get('/appointments', 'AppointmentController@index');

Route::get('/temporary-appointments', 'TemporaryAppointmentController@index');


// Front
Route::get('/web/appointment/clinic/{app_key}', 'Web\AppointmentController@clinic');
Route::post('/web/appointment/calendar/{app_key}', 'Web\AppointmentController@calendar');
Route::post('/web/appointment/{app_key}', 'Web\AppointmentController@store');
Route::delete('/web/appointment/{app_key}/{uuid}', 'Web\AppointmentController@cancel');
Route::post('/web/appointment/temporary/{app_key}', 'Web\AppointmentController@storeTemporary');
Route::get('/web/appointment/appointments/{patient_id}', 'Web\AppointmentController@appointments');


