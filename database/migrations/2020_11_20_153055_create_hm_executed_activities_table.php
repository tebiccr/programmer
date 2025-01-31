<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHmExecutedActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('hm_specialties', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('id_sigte');
        //     $table->bigInteger('id_n820');
        //     $table->string('specialty_name');
        //
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        Schema::table('hm_specialties', function (Blueprint $table) {
            $table->string('id_sigte')->after('id_specialty');
        });

        Schema::create('hm_executed_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('correlative');
            $table->datetime('programming_date')->nullable();
            $table->string('operating_room')->nullable();
            $table->integer('origin_request')->nullable();
            $table->string('origin_request_desc')->nullable();
            $table->string('profession');
            $table->string('medic_rut')->nullable();
            $table->string('medic_dv')->nullable();
            $table->string('medic_name');
            // $table->unsignedInteger('specialty_id');
            $table->string('medic_specialty')->nullable();
            $table->string('medic_specialty_desc')->nullable();
            $table->integer('intervention_procedure');
            $table->string('intervention_procedure_desc')->nullable();
            $table->integer('plane');
            $table->string('plane_desc')->nullable();
            $table->integer('extremity');
            $table->string('extremity_desc')->nullable();
            $table->integer('estimated_intervention_time');
            $table->datetime('tx_entrance_date')->nullable(); //FECHA_INGRESO_TX
            $table->integer('intervention_status');
            $table->string('intervention_status_desc')->nullable();
            $table->datetime('intervention_start_date')->nullable(); //FECHA_INICIO_INTERVENCION
            $table->datetime('intervention_end_date')->nullable(); //FECHA_TERMINO_INTERVENCION
            $table->integer('surgery_category')->nullable();
            $table->string('surgery_category_desc')->nullable();
            $table->datetime('operating_room_entrance_date')->nullable(); //FECHA_INGRESO_PABELLON
            $table->datetime('surgery_room_entrance_date')->nullable(); //FECHA_INGRESO_QUIROFANO
            $table->datetime('surgery_room_exit_date')->nullable(); //FECHA_SALIDA_QUIROFANO
            $table->string('table_surgery_category')->nullable();
            $table->string('table_surgery_category_desc')->nullable();
            $table->integer('suspencion_cause')->nullable();
            $table->string('suspencion_cause_desc')->nullable();
            // $table->integer('year')->nullable();

            // $table->foreign('specialty_id')->references('id')->on('hm_specialties');

            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hm_executed_activities');
        // Schema::dropIfExists('hm_specialties');
        Schema::table('hm_specialties', function (Blueprint $table) {
            $table->dropColumn('id_sigte');
        });
    }
}
