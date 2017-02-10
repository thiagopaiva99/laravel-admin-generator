<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentFn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
CREATE OR REPLACE FUNCTION fn2_format_appointment(id_appointment INTEGER)
  RETURNS available_appointment2
  LANGUAGE plpgsql
AS \$\$
DECLARE
  timeSlot time_slot2;
  rec RECORD;
BEGIN

  SELECT INTO rec *
  FROM
    appointments, time_slots, time_slot_details
  WHERE
    appointments.id = id_appointment AND
    appointments.time_slot_id = time_slots.id AND
    time_slot_details.time_slot_id = time_slots.id;

  timeSlot := NULL;
  timeSlot.day_of_week := rec.day_of_week;
  timeSlot.detail_id := rec.time_slot_detail_id;
  timeSlot.health_id := rec.health_plan_id;
  timeSlot.id := rec.id;
  timeSlot.local_id := rec.local_id;
  timeSlot.private := rec.private;
  timeSlot.queue_type := rec.queue_type;
  timeSlot.slot_count := rec.slot_count;
  timeSlot.slot_date := CAST(rec.appointment_start AS DATE);
  timeSlot.slot_time_end := rec.slot_time_end;
  timeSlot.slot_time_start := rec.slot_time_start;
  timeSlot.slot_type := rec.slot_type;
  timeSlot.user_id := rec.user_id;

  RETURN fn2_convert_time_slot2_to_available_appointment2(timeSlot);
END;
\$\$;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION IF EXISTS fn2_format_appointment(INTEGER);");
    }
}