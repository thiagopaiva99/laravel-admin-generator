<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNToMRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_plan_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('health_plan_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('health_plan_id')->references('id')->on('health_plans');
        });

        Schema::create('health_plan_local', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id');
            $table->integer('health_plan_id');

            $table->foreign('local_id')->references('id')->on('locals');
            $table->foreign('health_plan_id')->references('id')->on('health_plans');
        });

        Schema::create('local_specialization', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id');
            $table->integer('specialization_id');

            $table->foreign('local_id')->references('id')->on('locals');
            $table->foreign('specialization_id')->references('id')->on('specializations');
        });

        Schema::create('exam_local', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id');
            $table->integer('exam_id');

            $table->foreign('local_id')->references('id')->on('locals');
            $table->foreign('exam_id')->references('id')->on('exams');
        });

        DB::statement("
            CREATE OR REPLACE FUNCTION public.fn_available_appointments_for_date(dt date, id_local integer)
             RETURNS SETOF time without time zone
             LANGUAGE plpgsql
            AS \$function\$
            DECLARE
              reg RECORD;
            
            BEGIN
            
              FOR reg IN (SELECT * FROM fn_available_appointments_slots_for_date(dt, id_local)) LOOP
                RETURN NEXT reg.slot_time_start;
              END LOOP;
            
            END;
            \$function\$        
        ");

        DB::statement("        
            CREATE OR REPLACE FUNCTION public.fn_available_appointments_slots_for_date(dt date, id_local integer)
             RETURNS SETOF time_slots
             LANGUAGE plpgsql
            AS \$function\$
            DECLARE
              timeSlot time_slots%ROWTYPE;
              dayOfWeek INTEGER;
            BEGIN
              IF id_local IS NOT NULL THEN
                IF dt IS NULL THEN
                  dt := CURRENT_DATE;
                END IF;
            
                IF NOT EXISTS (SELECT 1 FROM closed_dates WHERE closed_dates.local_id = id_local AND dt BETWEEN closed_dates.start_datetime AND closed_dates.end_datetime AND closed_dates.deleted_at IS NULL) THEN
                  IF EXISTS (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) THEN
                    FOR timeSlot IN (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  ELSE
                    SELECT INTO dayOfWeek extract(DOW FROM dt);
                    FOR timeSlot IN (
                      SELECT time_slots.*
                      FROM time_slots
                      WHERE
                        time_slots.day_of_week = dayOfWeek AND
                        time_slots.local_id = id_local AND
                        time_slots.slot_type = 1 AND
                        time_slots.deleted_at IS NULL AND
                        NOT EXISTS
                        (
                            SELECT 1
                            FROM appointments
                            WHERE (
                                    (appointments.appointment_start AT TIME ZONE 'GMT' >= (dt + time_slots.slot_time_start AT TIME ZONE 'GMT') AND appointments.appointment_start AT TIME ZONE 'GMT' < (dt + time_slots.slot_time_end AT TIME ZONE 'GMT')) OR
                                    (appointments.appointment_end AT TIME ZONE 'GMT' <= (dt + time_slots.slot_time_end AT TIME ZONE 'GMT') AND appointments.appointment_end AT TIME ZONE 'GMT' > (dt + time_slots.slot_time_start AT TIME ZONE 'GMT'))
                                  ) AND appointments.deleted_at IS NULL
                        ) AND NOT EXISTS (
                            SELECT 1
                            FROM closed_dates
                            WHERE ((dt + time_slots.slot_time_start AT TIME ZONE 'GMT'), (dt + time_slots.slot_time_end AT TIME ZONE 'GMT')) OVERLAPS (closed_dates.start_datetime, closed_dates.end_datetime) AND
                                  closed_dates.local_id = time_slots.local_id AND
                                  closed_dates.deleted_at IS NULL
                        )
                      ORDER BY time_slots.slot_time_start
                    ) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  END IF;
                END IF;
              END IF;
            END;
            \$function\$
        ");

        DB::statement("        
            CREATE OR REPLACE FUNCTION public.fn_available_appointments_slots_for_date(dt date, id_local integer)
             RETURNS SETOF time_slots
             LANGUAGE plpgsql
            AS \$function\$
            DECLARE
              timeSlot time_slots%ROWTYPE;
              dayOfWeek INTEGER;
            BEGIN
              IF id_local IS NOT NULL THEN
                IF dt IS NULL THEN
                  dt := CURRENT_DATE;
                END IF;
            
                IF NOT EXISTS (SELECT 1 FROM closed_dates WHERE closed_dates.local_id = id_local AND dt BETWEEN closed_dates.start_datetime AND closed_dates.end_datetime AND closed_dates.deleted_at IS NULL) THEN
                  IF EXISTS (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) THEN
                    FOR timeSlot IN (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  ELSE
                    SELECT INTO dayOfWeek extract(DOW FROM dt);
                    FOR timeSlot IN (
                      SELECT time_slots.*
                      FROM time_slots
                      WHERE
                        time_slots.day_of_week = dayOfWeek AND
                        time_slots.local_id = id_local AND
                        time_slots.slot_type = 1 AND
                        time_slots.deleted_at IS NULL AND
                        NOT EXISTS
                        (
                            SELECT 1
                            FROM appointments
                            WHERE (
                                    (appointments.appointment_start AT TIME ZONE 'GMT' >= (dt + time_slots.slot_time_start AT TIME ZONE 'GMT') AND appointments.appointment_start AT TIME ZONE 'GMT' < (dt + time_slots.slot_time_end AT TIME ZONE 'GMT')) OR
                                    (appointments.appointment_end AT TIME ZONE 'GMT' <= (dt + time_slots.slot_time_end AT TIME ZONE 'GMT') AND appointments.appointment_end AT TIME ZONE 'GMT' > (dt + time_slots.slot_time_start AT TIME ZONE 'GMT'))
                                  ) AND appointments.deleted_at IS NULL
                        ) AND NOT EXISTS (
                            SELECT 1
                            FROM closed_dates
                            WHERE ((dt + time_slots.slot_time_start AT TIME ZONE 'GMT'), (dt + time_slots.slot_time_end AT TIME ZONE 'GMT')) OVERLAPS (closed_dates.start_datetime, closed_dates.end_datetime) AND
                                  closed_dates.local_id = time_slots.local_id AND
                                  closed_dates.deleted_at IS NULL
                        )
                      ORDER BY time_slots.slot_time_start
                    ) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  END IF;
                END IF;
              END IF;
            END;
            \$function\$
        ");

        DB::statement("        
            CREATE OR REPLACE FUNCTION public.fn_available_appointments_slots_for_date(dt timestamp without time zone, id_local integer)
             RETURNS SETOF time_slots
             LANGUAGE plpgsql
            AS \$function\$
              DECLARE
              timeSlot time_slots%ROWTYPE;
              dayOfWeek INTEGER;
            BEGIN
              IF id_local IS NOT NULL THEN
                IF dt IS NULL THEN
                  dt := CURRENT_TIMESTAMP;
                END IF;
            
                IF NOT EXISTS (SELECT 1 FROM closed_dates WHERE closed_dates.local_id = id_local AND dt BETWEEN closed_dates.start_datetime AND closed_dates.end_datetime AND closed_dates.deleted_at IS NULL) THEN
                  IF EXISTS (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) THEN
                    FOR timeSlot IN (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  ELSE
                    SELECT INTO dayOfWeek extract(DOW FROM dt);
                    FOR timeSlot IN (
                      SELECT * FROM time_slots WHERE time_slots.day_of_week = dayOfWeek AND time_slots.local_id = id_local AND time_slots.slot_type = 1 AND time_slots.deleted_at IS NULL AND NOT EXISTS
                      (
                          SELECT 1 FROM appointments WHERE (
                                                             (appointments.appointment_start >= dt AND appointments.appointment_start < dt) OR
                                                             (appointments.appointment_end <= dt AND appointments.appointment_end > dt)
                                                           ) AND appointments.deleted_at IS NULL
                      )
                    ) LOOP
                      RETURN NEXT timeSlot;
                    END LOOP;
                  END IF;
                END IF;
              END IF;
            END;
            \$function\$        
        ");

        DB::statement("        
            CREATE OR REPLACE FUNCTION public.fn_next_available_appointment_datetime(id_local integer)
             RETURNS timestamp without time zone
             LANGUAGE plpgsql
            AS \$function\$
                    DECLARE
                      reg RECORD;
                    BEGIN
            
                      FOR reg IN (SELECT * FROM fn_next_available_appointment_slot(id_local)) LOOP
                        RETURN reg.slot_date + reg.slot_time_start;
                      END LOOP;
            
                      RETURN NULL ;
                    END;
                    \$function\$    
        ");

        DB::statement("        
            CREATE OR REPLACE FUNCTION public.fn_next_available_appointment_slot(id_local integer)
             RETURNS time_slots
             LANGUAGE plpgsql
            AS \$function\$
                      DECLARE
                        timeSlot time_slots;
                        dt DATE;
                        maxDate DATE;
                      BEGIN
                        dt := CURRENT_DATE;
                        maxDate := dt + interval '3 months';
            
                        WHILE dt < maxDate LOOP
                          FOR timeSlot IN (SELECT * FROM fn_available_appointments_slots_for_date(dt, id_local)) LOOP
                            IF dt = CURRENT_DATE THEN
                              IF timeSlot.slot_time_start >= CAST(CURRENT_TIMESTAMP AS time without time zone) THEN
                                timeSlot.slot_date := dt;
                                RETURN timeSlot;
                                EXIT;
                              END IF;
                            ELSE
                              timeSlot.slot_date := dt;
                              RETURN timeSlot;
                              EXIT;
                            END IF;
                          END LOOP;
                          dt := dt + interval '1 day';
                        END LOOP;
            
                        RETURN NULL;
            
                      END;
                      \$function\$            
        ");

        DB::statement("        
            CREATE TYPE available_appointment AS (
              time_slot_id INTEGER,
              time_epoch DOUBLE PRECISION,
              time_header VARCHAR,
              time_label VARCHAR,
              time_label_formated VARCHAR
            );
        ");

        DB::statement("            
            CREATE OR REPLACE FUNCTION public.fn_next_availables_appointment(id_local integer)
             RETURNS SETOF available_appointment
             LANGUAGE plpgsql
            AS \$function\$
              DECLARE
                      timeSlot time_slots;
                      dt DATE;
                      maxDate DATE;
                      possible_appointment available_appointment;
                      dateTime TIMESTAMP;
                    BEGIN
                      --SET LC_TIME := 'pt_BR.UTF8';
                      SET LC_TIME = 'pt_BR.UTF8';
                      dt := CURRENT_DATE;
                      maxDate := dt + interval '50 days';
            
                      WHILE dt < maxDate LOOP
                        FOR timeSlot IN (SELECT * FROM fn_available_appointments_slots_for_date(dt, id_local) order by id desc) LOOP
                          IF dt = CURRENT_DATE THEN
                            IF timeSlot.slot_time_start > CURRENT_TIME THEN
                              dateTime := dt + timeSlot.slot_time_start;
                              possible_appointment := NULL;
                              possible_appointment.time_slot_id := timeSlot.id;
                              possible_appointment.time_epoch := EXTRACT(EPOCH FROM dateTime);
                              possible_appointment.time_header := UPPER(trim(to_char(dateTime, 'DD')) || ' DE ' || trim(to_char(dateTime, 'TMMONTH')) || ', ' || trim(to_char(dateTime, 'TMDAY')));
                              possible_appointment.time_label := to_char(dateTime, 'HH24:MI');
                              possible_appointment.time_label_formated := UPPER(trim(to_char(dateTime, 'DD')) || '/' || to_char(dateTime, 'MM') || ', ' || trim(to_char(dateTime, 'TMDY')) || ' - ' || to_char(dateTime, 'HH24:MI'));
                              RETURN NEXT possible_appointment;
                            END IF;
                          ELSE
                            dateTime := dt + timeSlot.slot_time_start;
                            possible_appointment := NULL;
                            possible_appointment.time_slot_id := timeSlot.id;
                            possible_appointment.time_epoch := EXTRACT(EPOCH FROM dateTime);
                            possible_appointment.time_header := UPPER(trim(to_char(dateTime, 'DD')) || ' DE ' || trim(to_char(dateTime, 'TMMONTH')) || ', ' || trim(to_char(dateTime, 'TMDAY')));
                            possible_appointment.time_label := to_char(dateTime, 'HH24:MI');
                            possible_appointment.time_label_formated := UPPER(trim(to_char(dateTime, 'DD')) || '/' || to_char(dateTime, 'MM') || ', ' || trim(to_char(dateTime, 'TMDY')) || ' - ' || to_char(dateTime, 'HH24:MI'));
                            RETURN NEXT possible_appointment;
                          END IF;
                        END LOOP;
                        dt := dt + interval '1 day';
                      END LOOP;
                    END;
            \$function\$            
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION fn_next_availables_appointment(id_local int4) CASCADE;");
        DB::statement("DROP FUNCTION fn_next_available_appointment_datetime(id_local INTEGER) CASCADE;");
        DB::statement("DROP FUNCTION fn_next_available_appointment_slot(id_local INTEGER) CASCADE;");
        DB::statement("DROP FUNCTION fn_available_appointments_for_date(dt DATE, id_local INTEGER) CASCADE;");
        DB::statement("DROP FUNCTION fn_available_appointments_slots_for_date(timestamp without time zone,integer) CASCADE;");
        DB::statement("DROP FUNCTION fn_available_appointments_slots_for_date(dt date, id_local int4) CASCADE;");
        DB::statement("DROP TYPE available_appointment;");

        Schema::drop("exam_local");
        Schema::drop("local_specialization");
        Schema::drop("health_plan_local");
        Schema::drop("health_plan_user");
    }
}
