<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     session(['id' => '123456']);
//     session(['name' => 'Esteban Rojas']);
//     session(['profile' => 'Administrador']);
//     return view('home');
// });

Route::get('/', 'HomeController@index')->name('home');
//
// Route::get('/', function()
// {
//     // dd(Input::get('login'));
//     if(Auth::attempt(['login' => Input::get('login'), 'password' => Input::get('password')]))
//     {
//         return Redirect::to('/welcome');
//     } else {
//         return "ERROOOOORRR!!";
//     }
// });

Auth::routes();

Route::prefix('ehr')->as('ehr.')->group(function(){
    Route::get('patient/forget','EHR\PatientController@forget')->name('patient.forget');
    Route::resource('patient','EHR\PatientController')->middleware('auth');
    Route::prefix('hetg')->as('hetg.')->middleware('auth')->group(function(){
        Route::resource('rrhh','EHR\HETG\RrhhController');
        Route::resource('contracts','EHR\HETG\ContractController');
        Route::resource('specialties','EHR\HETG\SpecialtyController');
        Route::resource('activities','EHR\HETG\ActivityController');
        Route::resource('mother_activities','EHR\HETG\MotherActivityController');
        Route::prefix('executed_activities')->as('executed_activities.')->group(function(){
            Route::get('/','EHR\HETG\ExecutedActivityController@index')->name('index');
        });
        Route::resource('medical_programming','EHR\HETG\MedicalProgrammingController');

        Route::match(['get', 'post'],'calendar_programming/saveMyEvent','EHR\HETG\CalendarProgrammingController@saveMyEvent')->name('calendar_programming.saveMyEvent');
        Route::match(['get', 'post'],'calendar_programming/deleteMyEvent','EHR\HETG\CalendarProgrammingController@deleteMyEvent')->name('calendar_programming.deleteMyEvent');
        Route::match(['get', 'post'],'calendar_programming/deleteMyEventForce','EHR\HETG\CalendarProgrammingController@deleteMyEventForce')->name('calendar_programming.deleteMyEventForce');

        Route::get('data/from/{Date?}','EHR\HETG\CalendarProgrammingController@getDataFromDate')->name('data.from');
        Route::match(['get', 'post'],'calendar_programming/calendar_programmer_report','EHR\HETG\CalendarProgrammingController@calendar_programmer_report')->name('calendar_programming.calendar_programmer_report');
        Route::resource('calendar_programming','EHR\HETG\CalendarProgrammingController');

        Route::match(['get', 'post'],'theoretical_programming/saveMyEvent','EHR\HETG\TheoreticalProgrammingController@saveMyEvent')->name('theoretical_programming.saveMyEvent');
        Route::match(['get', 'post'],'theoretical_programming/updateMyEvent','EHR\HETG\TheoreticalProgrammingController@updateMyEvent')->name('theoretical_programming.updateMyEvent');
        Route::match(['get', 'post'],'theoretical_programming/deleteMyEvent','EHR\HETG\TheoreticalProgrammingController@deleteMyEvent')->name('theoretical_programming.deleteMyEvent');
        Route::match(['get', 'post'],'theoretical_programming/deleteMyEventForce','EHR\HETG\TheoreticalProgrammingController@deleteMyEventForce')->name('theoretical_programming.deleteMyEventForce');

        Route::match(['get', 'post'],'operating_room_programming/saveMyEvent','EHR\HETG\OperatingRoomProgrammingController@saveMyEvent')->name('operating_room_programming.saveMyEvent');
        Route::match(['get', 'post'],'operating_room_programming/updateMyEvent','EHR\HETG\OperatingRoomProgrammingController@updateMyEvent')->name('operating_room_programming.updateMyEvent');
        Route::match(['get', 'post'],'operating_room_programming/deleteMyEvent','EHR\HETG\OperatingRoomProgrammingController@deleteMyEvent')->name('operating_room_programming.deleteMyEvent');
        Route::match(['get', 'post'],'operating_room_programming/deleteMyEventForce','EHR\HETG\OperatingRoomProgrammingController@deleteMyEventForce')->name('operating_room_programming.deleteMyEventForce');

        Route::resource('operating_room_programming','EHR\HETG\OperatingRoomProgrammingController');
        Route::resource('theoretical_programming','EHR\HETG\TheoreticalProgrammingController');
        Route::resource('operating_rooms','EHR\HETG\OperatingRoomController');
        Route::prefix('management')->as('management.')->group(function(){
            Route::get('/programmer','EHR\HETG\OperatingRoomController@programmer')->name('programmer');
            Route::prefix('report')->as('report.')->group(function(){
                Route::match(['get', 'post'],'/specialty','EHR\HETG\OperatingRoomController@reportSpecialty')->name('specialty');
                Route::match(['get', 'post'],'/by_profesional','EHR\HETG\OperatingRoomController@reportByProfesional')->name('by_profesional');
                Route::match(['get', 'post'],'/weekly','EHR\HETG\OperatingRoomController@reportWeekly')->name('weekly');
                Route::match(['get', 'post'],'/diary','EHR\HETG\OperatingRoomController@reportDiary')->name('diary');
                Route::match(['get', 'post'],'/report1','EHR\HETG\OperatingRoomController@report1')->name('report1');
                Route::match(['get', 'post'],'/urgency','EHR\HETG\OperatingRoomController@reportUrgency')->name('urgency');
            });
        });
    });

    Route::prefix('hfa')->as('hfa.')->middleware('auth')->group(function(){
        Route::get('signature/{model}/{id}/request','EHR\HFA\SignatureController@request')->name('signature.request');
        Route::get('signature/sign','EHR\HFA\SignatureController@sign')->name('signature.sign');
        Route::get('entry/{entry}/request_signature','EHR\HFA\EntryController@requestSignature')->name('entry.request_signature');
        Route::put('entry/{entry}/signature','EHR\HFA\EntryController@storeSignature')->name('entry.store_signature');
        Route::resource('entry','EHR\HFA\EntryController');
        Route::get('egress/{egress}/request_signature','EHR\HFA\EgressController@requestSignature')->name('egress.request_signature');
        Route::put('egress/{egress}/signature','EHR\HFA\EgressController@storeSignature')->name('egress.store_signature');
        Route::get('egress/create/{entry}', 'EHR\HFA\EgressController@create')->name('egress.create');
        Route::resource('egress','EHR\HFA\EgressController')->except(['create']);;
    });
});

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/reportcsv', 'ReportController@exportcsv')->name('reportcsv');
Route::get('/reportexcel', 'ReportController@export')->name('reportexcel');
