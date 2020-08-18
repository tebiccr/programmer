<?php

namespace App\Http\Controllers\EHR\HETG;

use App\EHR\HETG\MotherActivity;
use App\EHR\HETG\Activity;
use App\EHR\HETG\CalendarProgramming;
use App\EHR\HETG\Rrhh;
use App\EHR\HETG\Contract;
use App\EHR\HETG\MedicalProgramming;
use App\EHR\HETG\TheoreticalProgramming;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\EHR\HETG\OperatingRoom;

class TheoreticalProgrammingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Storage::put('errores.txt', "asdds");
        // for($i=$first_week;$i<=$last_week;$i++)
        // {
        //     $timestamp = mktime( 0, 0, 0, 1, 1,  $year ) + ( $i * 7 * 24 * 60 * 60 );
        //     $timestamp_for_day = $timestamp - 86400 * ( date( 'N', $timestamp ) - 1 );
        //     dd(date( 'Y-m-d', $timestamp_for_day ));
        //     $start_date = date( 'Y-m-d', $timestamp_for_day ) . " " . $start_time;
        //     $end_date = date( 'Y-m-d', $timestamp_for_day ) . " " . $end_time;
        //
        //     if (date('Y', strtotime($start_date)) == $year) {
        //         // $theoreticalProgramming = new TheoreticalProgramming();
        //         // $theoreticalProgramming->rut = $request->rut;
        //         // $theoreticalProgramming->activity_id = $request->activity_id;
        //         // $theoreticalProgramming->start_date = $start_date;
        //         // $theoreticalProgramming->end_date = $end_date;
        //         // $theoreticalProgramming->year = $year;
        //         // $theoreticalProgramming->user_id = session('yani_id');//Auth::user()->yani_id;//id;
        //         // $theoreticalProgramming->save();
        //         print_r($start_date . " ");
        //     }
        // }dd("");

      // if ($request->get('year')) {
      //   $year = $request->get('year');
      //   $rut = $request->get('rut');
      // }
      // else{
      //   $date = Carbon::now();
      //   $year = $date->format('Y');
      //   $rut = 0;
      // }
      //
      // $motherActivities = MotherActivity::where('id',2)->get();
      // $ids_actividades = $motherActivities->first()->activities->pluck('id')->toArray(); //se obtienen actividades de pabellón teórico
      // $theoricalProgrammings = TheoreticalProgramming::where('year',$year)
      //                                               ->where('rut',$rut)->get();
      //
      // //se obtiene fechas de inicio y termino de cada isEventOverDiv
      // foreach ($theoricalProgrammings as $key => $theoricalProgramming) {
      //   $week_day = $theoricalProgramming->week_day;
      //   if ($theoricalProgramming->week_day == 0) {
      //     $week_day = 7;
      //   }
      //   $theoricalProgramming->start_date = "1900-01-0". $week_day . " " . $theoricalProgramming->start_time;
      //   $theoricalProgramming->end_date = "1900-01-0". $week_day . " " . $theoricalProgramming->end_time;
      //
      //   $start  = new Carbon($theoricalProgramming->start_date);
      //   $end    = new Carbon($theoricalProgramming->end_date);
      //   $theoricalProgramming->duration_theorical_programming = $start->diffInMinutes($end)/60;
      // }
      //
      // //obtiene información de programas médicos asignados
      // $medicalProgrammings = MedicalProgramming::where('year',$year)
      //                                         ->where('rut',$rut)
      //                                         ->whereIn('activity_id',$ids_actividades)
      //                                         ->get();
      // $array = array();
      // foreach ($medicalProgrammings as $key => $medicalProgramming) {
      //   $array[$medicalProgramming->contract->first()->law] = $medicalProgrammings;
      // }
      //
      // // $rrhhs = Rrhh::whereHas('contracts', function ($query) use ($year) {
      // //                return $query->where('year',$year);
      // //             })->orderby('name','ASC')->get();
      //
      // $rrhhs = Rrhh::whereHas('contracts', function ($query) use ($year) {
      //                return $query;//->where('year',$year);
      //             })->orderby('name','ASC')->get();
      //
      //   //información para días contrato
      //   $contracts = Contract::where('rut',$rut)->get();
      //   // $rut = NULL;
      //   // if ($contracts) {
      //   //     $rut = $contracts->first()->rut;
      //   // }
      //   // dd($rut);
      //
      //   $contract_days = CalendarProgramming::where('rut',$rut)->whereNotNull('contract_day_type')->get();
      //   // dd($contract_days);
      //
      // return view('ehr.hetg.management.theoretical_programmer',compact('request','rrhhs','array','theoricalProgrammings','contracts','rut','contract_days'));








      //primer form
      if ($request->get('date')) {
        $date = $request->get('date');
        $year = $request->get('year');
        $rut = $request->get('rut');
      }
      else{
        $date = Carbon::now();
        $year = $date->get('year');
        $rut = $request->get('rut');
      }

    $motherActivities = MotherActivity::where('id',2)->get();
    $ids_actividades = $motherActivities->first()->activities->pluck('id')->toArray(); //se obtienen actividades de pabellón teórico

    $monday = Carbon::parse($date)->startOfWeek();
    $sunday = Carbon::parse($date)->endOfWeek();
    // dd($monday, $sunday);
    $theoreticalProgrammings = TheoreticalProgramming::where('year',$year)
                                                  ->where('rut',$rut)
                                                  ->whereBetween('start_date',[$monday,$sunday])
                                                  ->get();

      //se obtiene fechas de inicio y termino de cada isEventOverDiv
      foreach ($theoreticalProgrammings as $key => $theoricalProgramming) {
        $start  = new Carbon($theoricalProgramming->start_date);
        $end    = new Carbon($theoricalProgramming->end_date);
        $theoricalProgramming->duration_theorical_programming = $start->diffInMinutes($end)/60;
      }

    //obtiene información de programas médicos asignados
    $medicalProgrammings = MedicalProgramming::where('year',$year)
                                            ->where('rut',$rut)
                                            ->whereIn('activity_id',$ids_actividades)
                                            ->get();
    $array = array();
    foreach ($medicalProgrammings as $key => $medicalProgramming) {
      $array[$medicalProgramming->contract->first()->law] = $medicalProgrammings;
    }

    $rrhhs = Rrhh::whereHas('contracts', function ($query) use ($year) {
                   return $query;//->where('year',$year);
                })->orderby('name','ASC')->get();

    //información para días contrato
    $contracts = Contract::where('rut',$rut)->get();
    $contract_days = CalendarProgramming::where('rut',$rut)->whereNotNull('contract_day_type')->get();


    $monday = Carbon::parse($date)->startOfWeek();
    $sunday = Carbon::parse($date)->endOfWeek();

      return view('ehr.hetg.management.theoretical_programmer', compact('request','array','contract_days','date','theoreticalProgrammings', 'rrhhs'));
      // return view('ehr.hetg.management.theoretical_programmer',compact('request','rrhhs','array','theoricalProgrammings','contracts','rut','contract_days'));
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
     * Store a newly created resource in storage: se usa para guardar dias de contrato (feriados, etc.)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ### Validaciones ###

        //### Validación 1: que no se topen los dias con ya registrados
        $count1 = CalendarProgramming::where('rut',$request->rut)
                                     ->whereNotNull('contract_day_type')
                                     ->whereRaw('? between start_date and end_date', [$request->start_date])
                                     ->count();
        $count2 = CalendarProgramming::where('rut',$request->rut)
                                     ->whereRaw('? between start_date and end_date', [$request->end_date])
                                     ->count();
        if (($count1 + $count2) != 0) {
            session()->flash('danger', 'Ya existe días administrativos registrados en esas fechas.');
            return redirect()->back();
        }


        //### Validacion 2: que no excedan los dias de los contratos -

        //obtiene días que se registrarán (request)
        $start  = new Carbon($request->start_date);
        $end    = new Carbon($request->end_date);
        $register_days = $start->diffInDays($end)+1;

        //se obtienen los permisos de contrato ya candelarizados
        $contract_days = CalendarProgramming::where('rut',$request->rut)
                                            ->whereNotNull('contract_day_type')
                                            ->whereYear('start_date', $request->year)
                                            ->get();
        $matrix = [];
        foreach ($contract_days as $key => $contract_day) {
            $matrix[$contract_day->contract_day_type]['cantidad'] = 0;
        }

        //cantidad de días calendarizados por cada tipo de permiso
        foreach ($contract_days as $key => $contract_day) {
            $start  = new Carbon($contract_day->start_date);
            $end    = new Carbon($contract_day->end_date);
            $matrix[$contract_day->contract_day_type]['cantidad'] += floor($start->diffInDays($end)) + 1;
        }

        //se suma cantidad que viene en el request, para asi validar abajo si se peude ingresar o no
        foreach ($matrix as $key => $dd) {
            if ($key == $request->contract_day_type) {
                $matrix[$key]['cantidad'] += $register_days;
            }
        }

        //se valida si las cantidades están dentro del margen de los contratado

        //se obtiene infor de contratos
        $contract_matrix['legal_holidays'] = 0;
        $contract_matrix['compensatory_rest'] = 0;
        $contract_matrix['administrative_permit'] = 0;
        $contract_matrix['training_days'] = 0;

        $contracts = Contract::where('rut',$request->rut)
                             ->where('year',$request->year)
                             ->get();
        foreach ($contracts as $key => $contract) {
            $contract_matrix['legal_holidays'] += $contract->legal_holidays;
            $contract_matrix['compensatory_rest'] += $contract->compensatory_rest;
            $contract_matrix['administrative_permit'] += $contract->administrative_permit;
            $contract_matrix['training_days'] += $contract->training_days;
        }

        //se verifica si exceden cantidades según sumatoria de contratos
        foreach ($matrix as $key => $value) {
            if($key == "legal_holidays"){
                if ($value['cantidad'] > $contract_matrix['legal_holidays']) {
                    session()->flash('danger', 'Se superó la cantidad de feriados legales.');
                    return redirect()->back();
                }
            }
            if($key == "compensatory_rest"){
                if ($value['cantidad'] > $contract_matrix['compensatory_rest']) {
                    session()->flash('danger', 'Se superó la cantidad de días descanzo compensatorio.');
                    return redirect()->back();
                }
            }
            if($key == "administrative_permit"){
                if ($value['cantidad'] > $contract_matrix['administrative_permit']) {
                    session()->flash('danger', 'Se superó la cantidad de días de permiso administrativos.');
                    return redirect()->back();
                }
            }
            if($key == "training_days"){
                if ($value['cantidad'] > $contract_matrix['training_days']) {
                    session()->flash('danger', 'Se superó la cantidad de días de congreso o capacitación.');
                    return redirect()->back();
                }
            }
        }




        //### Se ingresa información del evento
        $calendarProgramming = new CalendarProgramming($request->all());
        $calendarProgramming->end_date = $request->end_date . " 23:59:59";
        $calendarProgramming->user_id = session('yani_id');//Auth::user()->yani_id;//id;
        $calendarProgramming->save();

        session()->flash('info', 'El evento ha sido creado.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function saveMyEvent(Request $request){
        $year = $request->year;
        $first_date = new Carbon($request->start_date);
        $last_date = new Carbon($request->end_date);

        //solo se inserta en esta semana
        if ($request->tipo == 1) {
            $theoreticalProgramming = new TheoreticalProgramming();
            $theoreticalProgramming->rut = $request->rut;
            $theoreticalProgramming->activity_id = $request->activity_id;
            $theoreticalProgramming->start_date = $first_date;
            $theoreticalProgramming->end_date = $last_date;
            $theoreticalProgramming->year = $year;
            $theoreticalProgramming->user_id = session('yani_id');//Auth::user()->yani_id;//id;
            $theoreticalProgramming->save();
        }
        //se inserta desde esta semana hacia adelante
        else {
            while (date('Y', strtotime($first_date)) == $year) {
                $theoreticalProgramming = new TheoreticalProgramming();
                $theoreticalProgramming->rut = $request->rut;
                $theoreticalProgramming->activity_id = $request->activity_id;
                $theoreticalProgramming->start_date = $first_date;
                $theoreticalProgramming->end_date = $last_date;
                $theoreticalProgramming->year = $year;
                $theoreticalProgramming->user_id = session('yani_id');//Auth::user()->yani_id;//id;
                $theoreticalProgramming->save();

                $first_date = $first_date->addWeek(1);
                $last_date = $last_date->addWeek(1);
            }
        }

    }

    public function updateMyEvent(Request $request){
        try {

          // $this->buildXMLHeader();
          $year = $request->year;
          $start_date = new Carbon($request->start_date);
          $end_date = new Carbon($request->end_date);
          $start_date_start = new Carbon($request->start_date_start);
          $end_date_start = new Carbon($request->end_date_start);

          //solo se modifica el evento actual
          if ($request->tipo == 1) {
              $theoreticalProgramming = TheoreticalProgramming::where('rut',$request->rut)
                                                              ->where('activity_id',$request->activity_id)
                                                              ->where('start_date',$start_date_start)
                                                              ->where('end_date',$end_date_start)->first();
              $theoreticalProgramming->start_date = $start_date;
              $theoreticalProgramming->end_date = $end_date;
              $theoreticalProgramming->save();
          }
          //se modifican todos los eventos hacia adelante
          else{
              while (date('Y', strtotime($start_date)) == $year) {
                  $theoreticalProgramming = TheoreticalProgramming::where('rut',$request->rut)
                                                                  ->where('activity_id',$request->activity_id)
                                                                  ->where('start_date',$start_date_start)
                                                                  ->where('end_date',$end_date_start)->first();
                  $theoreticalProgramming->start_date = $start_date;
                  $theoreticalProgramming->end_date = $end_date;
                  $theoreticalProgramming->save();

                  $start_date = $start_date->addWeek(1);
                  $end_date = $end_date->addWeek(1);
                  $start_date_start = $start_date_start->addWeek(1);
                  $end_date_start = $end_date_start->addWeek(1);
              }
          }

        } catch (\Exception $e) {

            // return $e->getMessage();
            Storage::put('errores.txt', $e->getMessage());
        }
    }

    //elimina el dato - queda respaldo de la eliminación
    public function deleteMyEvent(Request $request){
      $year = $request->year;
      $first_date = new Carbon($request->start_date);
      $last_date = new Carbon($request->end_date);

      //solo se elimina el evento actual
        if ($request->tipo == 1) {
            $theoreticalProgramming = TheoreticalProgramming::where('rut',$request->rut)
                                                            ->where('activity_id',$request->activity_id)
                                                            ->where('start_date',$first_date)
                                                            ->where('end_date',$last_date);
            $theoreticalProgramming->delete();
        }
        //se elimina desde el evento actual
        else{
            while (date('Y', strtotime($first_date)) == $year) {
                $theoreticalProgramming = TheoreticalProgramming::where('rut',$request->rut)
                                                                ->where('activity_id',$request->activity_id)
                                                                ->where('start_date',$first_date)
                                                                ->where('end_date',$last_date);
                $theoreticalProgramming->delete();

                $first_date = $first_date->addWeek(1);
                $last_date = $last_date->addWeek(1);
            }
        }
    }

    //elimina el dato (cuando son movimientos en el calendario) - no queda respaldo
    public function deleteMyEventForce(Request $request){
      $year = $request->year;
      $first_date = new Carbon($request->start_date);
      $last_date = new Carbon($request->end_date);
      while (date('Y', strtotime($first_date)) == $year) {
          $theoreticalProgramming = TheoreticalProgramming::where('rut',$request->rut)
                                                          ->where('activity_id',$request->activity_id)
                                                          ->where('start_date',$first_date)
                                                          ->where('end_date',$last_date);
          $theoreticalProgramming->forceDelete();

          $first_date = $first_date->addWeek(1);
          $last_date = $last_date->addWeek(1);
      }
    }

    /*función que reemplaza espacios en blanco, puntos y parentesis por _ - además quita tildes **/
    public function formatear_cadena($cadena) {
      $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
      $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
      $texto = str_replace($no_permitidas, $permitidas ,$cadena);
      $texto = str_replace('}', '', str_replace('{', '', str_replace(',', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace(' ', '_', $texto)))))));
      return $texto;
    }
}
