<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SqlFunctionsFase2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
CREATE TYPE available_appointment2 AS (
  time_slot_id INTEGER,
  time_epoch DOUBLE PRECISION,
  time_header VARCHAR,
  time_label VARCHAR,
  time_label_formated VARCHAR,
  time_sublabel_formated VARCHAR,
  time_slot_count INTEGER,
  time_slot_detail_id INTEGER,
  time_subtitle VARCHAR,
  time_slot_for_queue BOOLEAN
);");

        DB::statement("
CREATE TYPE time_slot2 AS (
    id INTEGER,
  local_id INTEGER,
  user_id INTEGER,
  slot_type INTEGER,
  day_of_week INTEGER,
  slot_time_start TIME WITH TIME ZONE,
  slot_time_end TIME WITH TIME ZONE,
  slot_date DATE,
  queue_type INTEGER,
  detail_id INTEGER,
  slot_count INTEGER,
  private BOOLEAN,
  health_id INTEGER
);");

        DB::statement("
CREATE OR REPLACE FUNCTION fn2_check_available_slot_count(detail_id INTEGER)
  RETURNS INTEGER
  LANGUAGE plpgsql
  AS \$\$
  DECLARE
  unavailable_count INTEGER;
    count INTEGER;
  BEGIN
    SELECT INTO count COALESCE(slot_count,0) FROM time_slot_details WHERE id = detail_id;
    SELECT INTO unavailable_count COUNT(*) FROM appointments WHERE appointments.time_slot_detail_id = detail_id AND deleted_at IS NULL;
    RETURN count - unavailable_count;
  END;
\$\$;
");

        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_parse_time_slot(timeSlotId INTEGER , pvt BOOLEAN, health_id INTEGER)
RETURNS SETOF time_slot2
LANGUAGE plpgsql
AS \$\$
  DECLARE
  timeSlot time_slots%ROWTYPE;
    retorno time_slot2;
    timeSlotDetail time_slot_details%ROWTYPE;
  BEGIN
    SELECT INTO timeSlot * FROM time_slots WHERE id = timeSlotId;

    IF timeSlot.queue_type = 1 THEN
      -- FILA
      FOR timeSlotDetail IN (
          SELECT
          *
          FROM
          time_slot_details
        WHERE
          time_slot_details.time_slot_id = timeSlot.id AND
          time_slot_details.private = pvt AND
          time_slot_details.health_plan_id = health_id AND
          fn2_check_available_slot_count(time_slot_details.id) > 0
      ) LOOP
        retorno := NULL;
        retorno.id := timeSlot.id;
        retorno.local_id := timeSlot.local_id;
        retorno.user_id := timeSlot.user_id;
        retorno.slot_type := timeSlot.slot_type;
        retorno.day_of_week := timeSlot.day_of_week;
        retorno.slot_time_start := timeSlot.slot_time_start;
        retorno.slot_time_end := timeSlot.slot_time_end;
        retorno.slot_date := timeSlot.slot_date;
        retorno.queue_type := timeSlot.queue_type;

        retorno.detail_id := timeSlotDetail.id;
        retorno.slot_count := fn2_check_available_slot_count(timeSlotDetail.id);
        retorno.private := timeSlotDetail.private;
        retorno.health_id := timeSlotDetail.health_plan_id;
        RETURN NEXT retorno;
      END LOOP;
    ELSE
      -- HORA MARCADA
      SELECT INTO timeSlotDetail *
    FROM
        time_slot_details
      WHERE
        time_slot_id = timeSlot.id AND
        fn2_check_available_slot_count(time_slot_details.id) > 0
      ORDER BY
        id DESC
      LIMIT 1;
      IF pvt IS TRUE THEN
        IF timeSlotDetail.private IS TRUE THEN
          retorno := NULL;
          retorno.id := timeSlot.id;
          retorno.local_id := timeSlot.local_id;
          retorno.user_id := timeSlot.user_id;
          retorno.slot_type := timeSlot.slot_type;
          retorno.day_of_week := timeSlot.day_of_week;
          retorno.slot_time_start := timeSlot.slot_time_start;
          retorno.slot_time_end := timeSlot.slot_time_end;
          retorno.slot_date := timeSlot.slot_date;
          retorno.queue_type := timeSlot.queue_type;

          retorno.detail_id := timeSlotDetail.id;
          retorno.slot_count := fn2_check_available_slot_count(timeSlotDetail.id);
          retorno.private := timeSlotDetail.private;
          retorno.health_id := timeSlotDetail.health_plan_id;
          RETURN NEXT retorno;
        END IF;
    ELSE
        IF timeSlotDetail.health_plan_id = health_id THEN
          retorno := NULL;
          retorno.id := timeSlot.id;
          retorno.local_id := timeSlot.local_id;
          retorno.user_id := timeSlot.user_id;
          retorno.slot_type := timeSlot.slot_type;
          retorno.day_of_week := timeSlot.day_of_week;
          retorno.slot_time_start := timeSlot.slot_time_start;
          retorno.slot_time_end := timeSlot.slot_time_end;
          retorno.slot_date := timeSlot.slot_date;
          retorno.queue_type := timeSlot.queue_type;

          retorno.detail_id := timeSlotDetail.id;
          retorno.slot_count := fn2_check_available_slot_count(timeSlotDetail.id);
          retorno.private := timeSlotDetail.private;
          retorno.health_id := timeSlotDetail.health_plan_id;
          RETURN NEXT retorno;
        END IF;
      END IF;
    END IF;
  END;
\$\$;");

        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_available_appointments_slots_for_date(dt date, id_local integer, pvt boolean, health_id integer)
  RETURNS SETOF time_slot2
LANGUAGE plpgsql
AS \$function\$
DECLARE
  retorno time_slot2;
  timeSlot time_slots%ROWTYPE;
  timeSlotDetail time_slot_details%ROWTYPE;
  dayOfWeek INTEGER;
BEGIN
  IF id_local IS NOT NULL THEN
    IF dt IS NULL THEN
      dt := CURRENT_DATE;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM closed_dates WHERE closed_dates.local_id = id_local AND dt BETWEEN closed_dates.start_datetime AND closed_dates.end_datetime AND closed_dates.deleted_at IS NULL LIMIT 1) THEN
      IF EXISTS (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL LIMIT 1) THEN
        FOR timeSlot IN (SELECT * FROM time_slots WHERE time_slots.slot_date = dt AND time_slots.local_id = id_local AND time_slots.slot_type = 2 AND time_slots.deleted_at IS NULL AND time_slots.id NOT IN (SELECT appointments.time_slot_id FROM appointments WHERE appointments.deleted_at IS NULL) AND time_slots.deleted_at IS NULL) LOOP
          FOR retorno IN (
            SELECT * FROM fn2_parse_time_slot(timeSlot.id, pvt, health_id)
          ) LOOP
            retorno.slot_date := dt;
            RETURN NEXT retorno;
          END LOOP;
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
            NOT EXISTS (
                SELECT 1
                FROM closed_dates
                WHERE ((dt + time_slots.slot_time_start AT TIME ZONE 'GMT'), (dt + time_slots.slot_time_end AT TIME ZONE 'GMT')) OVERLAPS (closed_dates.start_datetime, closed_dates.end_datetime) AND
                      closed_dates.local_id = time_slots.local_id AND
                      closed_dates.deleted_at IS NULL
            )
          ORDER BY time_slots.slot_time_start
        ) LOOP
          FOR retorno IN (
            SELECT * FROM fn2_parse_time_slot(timeSlot.id, pvt, health_id)
          ) LOOP
            retorno.slot_date := dt;
            RETURN NEXT retorno;
          END LOOP;
        END LOOP;
      END IF;
    END IF;
  END IF;
END;
\$function\$;
");

        DB::statement("
CREATE OR REPLACE FUNCTION fn2_next_availables_appointments_slots_for_user(id_user INTEGER, id_local INTEGER)
  RETURNS SETOF time_slot2
LANGUAGE plpgsql
AS \$\$
DECLARE
  retorno time_slot2;
  healthPlanRow health_plans%ROWTYPE;
  dt DATE;
  pvt BOOLEAN;
  sqlText TEXT;
  startDateTime TIMESTAMP WITH TIME ZONE;
  endDateTime TIMESTAMP WITH TIME ZONE;
BEGIN
  dt := CURRENT_DATE;

  SELECT INTO pvt private_health_plan FROM users WHERE id = id_user;

  WHILE dt < CURRENT_DATE + interval '60 days'
  LOOP
    IF pvt THEN
      FOR retorno IN
      SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, true, -1)
      LOOP
        startDateTime := retorno.slot_date + retorno.slot_time_start;
        endDateTime := retorno.slot_date + retorno.slot_time_end;

        IF retorno.queue_type = 1 THEN
          -- fila
          IF retorno.slot_date > current_date or CURRENT_TIMESTAMP < endDateTime THEN
            return next retorno;
          END IF;
        ELSE
          -- hora marcada
          IF startDateTime >= CURRENT_TIMESTAMP THEN
            return next retorno;
          END IF;
        END IF; 
      END LOOP;
    ELSE
      IF EXISTS(SELECT
                  1
                FROM
                  health_plans, health_plan_user
                WHERE
                  health_plans.id = health_plan_user.health_plan_id AND
                  health_plan_user.user_id = id_user LIMIT 1) THEN

        sqlText := '';

        FOR healthPlanRow IN
        SELECT
          health_plans.*
        FROM
          health_plans, health_plan_user
        WHERE
          health_plans.id = health_plan_user.health_plan_id AND
          health_plan_user.user_id = id_user
        LOOP
          IF sqlText <> '' THEN
            sqlText := sqlText || ' UNION ';
          END IF;

          sqlText := sqlText || 'SELECT * FROM fn2_available_appointments_slots_for_date('''|| to_char(dt,'yyyy-mm-dd') ||''', '|| id_local ||', false, '|| healthPlanRow.id ||')';
        END LOOP;

        sqlText := 'SELECT * FROM (' || sqlText || ') AS slots ORDER BY slot_date + slot_time_start';

        FOR retorno IN
        EXECUTE sqlText
        LOOP
          startDateTime := retorno.slot_date + retorno.slot_time_start;
          endDateTime := retorno.slot_date + retorno.slot_time_end;

          IF retorno.queue_type = 1 THEN
            -- fila
            IF retorno.slot_date > current_date or CURRENT_TIMESTAMP < endDateTime THEN
              return next retorno;
            END IF;
          ELSE
            -- hora marcada
            IF startDateTime >= CURRENT_TIMESTAMP THEN
              return next retorno;
            END IF;
          END IF;        
        END LOOP;
      END IF;
    END IF;

    dt := dt + INTERVAL '1 day';

  END LOOP;

END;
\$\$;
        ");

        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_next_availables_appointments_slots_for_user(id_user integer, id_local integer, appointments_count integer)
  RETURNS SETOF time_slot2
LANGUAGE plpgsql
AS \$function\$
DECLARE
  retorno time_slot2;
  healthPlanRow health_plans%ROWTYPE;
  dt DATE;
  pvt BOOLEAN;
  sqlText TEXT;
  startDateTime TIMESTAMP WITH TIME ZONE;
  endDateTime TIMESTAMP WITH TIME ZONE;
  count INTEGER;
BEGIN
  dt := CURRENT_DATE;
  count := 0;

  SELECT INTO pvt private_health_plan FROM users WHERE id = id_user;

  WHILE dt < CURRENT_DATE + interval '60 days' AND count < appointments_count
  LOOP
    IF pvt THEN
      FOR retorno IN
      SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, true, -1)
      LOOP
        startDateTime := retorno.slot_date + retorno.slot_time_start;
        endDateTime := retorno.slot_date + retorno.slot_time_end;

        IF retorno.queue_type = 1 THEN
          -- fila
          IF retorno.slot_date > current_date or CURRENT_TIMESTAMP < endDateTime THEN
            count := count + 1;
            return next retorno;
            IF count = appointments_count THEN
              EXIT;
            END IF;
          END IF;
        ELSE
          -- hora marcada
          IF startDateTime >= CURRENT_TIMESTAMP THEN
            count := count + 1;
            return next retorno;
            IF count = appointments_count THEN
              EXIT;
            END IF;
          END IF;
        END IF;
      END LOOP;
    ELSE
      IF EXISTS(SELECT
                  1
                FROM
                  health_plans, health_plan_user
                WHERE
                  health_plans.id = health_plan_user.health_plan_id AND
                  health_plan_user.user_id = id_user LIMIT 1) THEN

        sqlText := '';

        FOR healthPlanRow IN
        SELECT
          health_plans.*
        FROM
          health_plans, health_plan_user
        WHERE
          health_plans.id = health_plan_user.health_plan_id AND
          health_plan_user.user_id = id_user
        LOOP
          IF sqlText <> '' THEN
            sqlText := sqlText || ' UNION ';
          END IF;

          sqlText := sqlText || 'SELECT * FROM fn2_available_appointments_slots_for_date('''|| to_char(dt,'yyyy-mm-dd') ||''', '|| id_local ||', false, '|| healthPlanRow.id ||')';
        END LOOP;

        sqlText := 'SELECT * FROM (' || sqlText || ') AS slots ORDER BY slot_date + slot_time_start';

        FOR retorno IN
        EXECUTE sqlText
        LOOP
          startDateTime := retorno.slot_date + retorno.slot_time_start;
          endDateTime := retorno.slot_date + retorno.slot_time_end;

          IF retorno.queue_type = 1 THEN
            -- fila
            IF retorno.slot_date > current_date or CURRENT_TIMESTAMP < endDateTime THEN
              count := count + 1;
              return next retorno;
              IF count = appointments_count THEN
                EXIT;
              END IF;
            END IF;
          ELSE
            -- hora marcada
            IF startDateTime >= CURRENT_TIMESTAMP THEN
              count := count + 1;
              return next retorno;
              IF count = appointments_count THEN
                EXIT;
              END IF;
            END IF;
          END IF;
        END LOOP;
      END IF;
    END IF;

    dt := dt + INTERVAL '1 day';

  END LOOP;

END;
\$function\$;
        ");

        DB::statement("
CREATE OR REPLACE FUNCTION fn2_convert_time_slot2_to_available_appointment2(timeSlot time_slot2)
  RETURNS available_appointment2
LANGUAGE plpgsql
AS \$\$
DECLARE
  retorno available_appointment2;
  dateTime TIMESTAMP WITH TIME ZONE ;
  endDateTime TIMESTAMP WITH TIME ZONE ;
  referenceHour INTEGER;
  turn VARCHAR;
BEGIN
  SET LC_TIME = 'pt_BR.UTF8';

  retorno := NULL;
  retorno.time_slot_count := timeSlot.slot_count;
  retorno.time_slot_detail_id := timeSlot.detail_id;

  dateTime := (timeSlot.slot_date + (timeSlot.slot_time_start AT TIME ZONE 'GMT'));

  IF timeSlot.queue_type = 1 THEN
    retorno.time_slot_for_queue := TRUE;

    endDateTime := (timeSlot.slot_date + (timeSlot.slot_time_end AT TIME ZONE 'GMT'));

    SELECT INTO referenceHour EXTRACT(HOUR FROM dateTime);

    turn := (CASE WHEN referenceHour BETWEEN 0 AND 12 THEN 'Manhã' ELSE 'Tarde' END);

    retorno.time_slot_id := timeSlot.id;
    retorno.time_epoch := EXTRACT(EPOCH FROM dateTime);
    retorno.time_header := UPPER(trim(to_char(dateTime, 'DD')) || ' DE ' || trim(to_char(dateTime, 'TMMONTH')) || ', ' || trim(to_char(dateTime, 'TMDAY')));
    retorno.time_label := to_char(dateTime, 'HH24:MI') || ' às ' || to_char(endDateTime, 'HH24:MI');
    retorno.time_label_formated := UPPER(trim(to_char(dateTime, 'DD')) || '/' || to_char(dateTime, 'MM') || ', ' || trim(to_char(dateTime, 'TMDY')));
    retorno.time_sublabel_formated := turn || ' das ' || to_char(dateTime, 'HH24:MI') || ' às ' || to_char(endDateTime, 'HH24:MI');
    retorno.time_subtitle := (SELECT name FROM health_plans WHERE id = timeSlot.health_id);

  ELSIF timeSlot.queue_type = 2 THEN
    retorno.time_slot_for_queue := FALSE;
    retorno.time_slot_id := timeSlot.id;
    retorno.time_epoch := EXTRACT(EPOCH FROM dateTime);
    retorno.time_header := UPPER(trim(to_char(dateTime, 'DD')) || ' DE ' || trim(to_char(dateTime, 'TMMONTH')) || ', ' || trim(to_char(dateTime, 'TMDAY')));
    retorno.time_label := to_char(dateTime, 'HH24:MI');
    retorno.time_label_formated := UPPER(trim(to_char(dateTime, 'DD')) || '/' || to_char(dateTime, 'MM') || ', ' || trim(to_char(dateTime, 'TMDY')) || ' - ' || to_char(dateTime, 'HH24:MI'));
    retorno.time_sublabel_formated := '';
    retorno.time_subtitle := '';
  END IF;

  return retorno;
END;
\$\$;
");
        
        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_next_availables_appointments_for_user(id_user integer, id_local integer)
  RETURNS SETOF available_appointment2
LANGUAGE plpgsql
AS \$\$
DECLARE
  retorno time_slot2;
  parsed available_appointment2;
  dt DATE;
  dateTime TIMESTAMPTZ;
  healthPlan health_plans%ROWTYPE;
BEGIN

  IF id_user > 0 THEN
    FOR retorno IN
    SELECT * FROM fn2_next_availables_appointments_slots_for_user(id_user, id_local)
    LOOP
      SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
      RETURN NEXT parsed;
    END LOOP;
  ELSE
    dt := CURRENT_DATE;
    WHILE dt < CURRENT_DATE + interval '60 days'
    LOOP
      FOR retorno IN
        SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, TRUE , -1)
      LOOP
        dateTime := retorno.slot_date + retorno.slot_time_start;
        IF dateTime >= CURRENT_TIMESTAMP THEN
          SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
          RETURN NEXT parsed;
        END IF;
      END LOOP;

      FOR healthPlan IN
        SELECT health_plans.* FROM health_plans, health_plan_local WHERE health_plans.health_plan_id = health_plan_local.health_plan_id AND health_plan_local.local_id = id_local
      LOOP
        FOR retorno IN
        SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, false , healthPlan.id)
        LOOP
          dateTime := retorno.slot_date + retorno.slot_time_start;
          IF dateTime >= CURRENT_TIMESTAMP THEN
            SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
            RETURN NEXT parsed;
          END IF;
        END LOOP;
      END LOOP;

      dt := dt + INTERVAL '1 day';
    END LOOP ;
  END IF;
END;
\$\$        
");

        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_next_availables_appointments_for_user(id_user integer, id_local integer, appointments_count integer)
  RETURNS SETOF available_appointment2
LANGUAGE plpgsql
AS \$function\$
DECLARE
  retorno time_slot2;
  parsed available_appointment2;
  dt DATE;
  dateTime TIMESTAMPTZ;
  healthPlan health_plans%ROWTYPE;
  count INTEGER;
BEGIN
  count := 0;

  IF id_user > 0 THEN
    FOR retorno IN
    SELECT * FROM fn2_next_availables_appointments_slots_for_user(id_user, id_local, appointments_count)
    LOOP
      SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
      RETURN NEXT parsed;
    END LOOP;
  ELSE
    dt := CURRENT_DATE;
    WHILE dt < CURRENT_DATE + interval '60 days' AND count < appointments_count
    LOOP
      FOR retorno IN
      SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, TRUE , -1)
      LOOP
        dateTime := retorno.slot_date + retorno.slot_time_start;
        IF dateTime >= CURRENT_TIMESTAMP THEN
          SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
          count := count + 1;
          RETURN NEXT parsed;
          IF count = appointments_count THEN
            EXIT;
          END IF;
        END IF;
      END LOOP;

      FOR healthPlan IN
      SELECT health_plans.* FROM health_plans, health_plan_local WHERE health_plans.health_plan_id = health_plan_local.health_plan_id AND health_plan_local.local_id = id_local
      LOOP
        FOR retorno IN
        SELECT * FROM fn2_available_appointments_slots_for_date(dt, id_local, false , healthPlan.id)
        LOOP
          dateTime := retorno.slot_date + retorno.slot_time_start;
          IF dateTime >= CURRENT_TIMESTAMP THEN
            SELECT INTO parsed * FROM fn2_convert_time_slot2_to_available_appointment2(retorno);
            count := count + 1;
            RETURN NEXT parsed;
            IF count = appointments_count THEN
              EXIT;
            END IF;
          END IF;
        END LOOP;
      END LOOP;

      dt := dt + INTERVAL '1 day';
    END LOOP ;
  END IF;
END;
\$function\$;        ");

        DB::statement("
CREATE OR REPLACE FUNCTION public.fn2_next_available_appointment_for_user(id_user integer, id_local integer, start_date date DEFAULT ('now'::text)::date, try_count integer DEFAULT 0)
 RETURNS available_appointment2
 LANGUAGE plpgsql
AS \$function\$
DECLARE
  rec RECORD;
  timeSlot time_slot2;
BEGIN
  
  IF try_count IS NULL THEN
    try_count := 0;
  END IF;
   
  IF try_count < 3 THEN
  
    IF id_user > 0 THEN
      SELECT
        INTO rec *
      FROM
        (
          WITH next_appointments AS (
              SELECT
                time_slots.id,
                time_slots.local_id,
                time_slots.user_id,
                time_slots.day_of_week,
                time_slots.slot_time_start,
                time_slots.slot_time_end,
                time_slots.queue_type,
                time_slots.slot_type,
                time_slot_details.health_plan_id,
                time_slot_details.private,
                time_slot_details.slot_count,
                time_slot_details.id AS detail_id,
                (CASE
                 WHEN time_slots.slot_type = 1
                   THEN
                     CAST(
                         (
                           CASE
                           WHEN EXTRACT(DOW FROM start_date) <= time_slots.day_of_week
                             THEN
                               start_date - (EXTRACT(DOW FROM start_date) * '1 day' :: INTERVAL) +
                               (time_slots.day_of_week * '1 day' :: INTERVAL)
                           ELSE
                             start_date - (EXTRACT(DOW FROM start_date) * '1 day' :: INTERVAL) +
                             (time_slots.day_of_week * '1 day' :: INTERVAL) + (7 * '1 day' :: INTERVAL)
                           END
                         ) AS DATE
                     )
                 ELSE
                   time_slots.slot_date
                 END) AS appointment_date,
                fn2_check_available_slot_count(time_slot_details.id) AS free
              FROM
                time_slots,
                time_slot_details,
                users patient
              WHERE
                time_slots.id = time_slot_details.time_slot_id AND
                time_slots.local_id = id_local AND
                patient.id = id_user AND
                time_slots.deleted_at IS NULL AND
                (
                  (time_slot_details.private IS TRUE AND patient.private_health_plan) OR
                  time_slot_details.health_plan_id IN (SELECT health_plan_id
                                                       FROM health_plan_user
                                                       WHERE health_plan_user.user_id = patient.id)
                ) AND
                fn2_check_available_slot_count(time_slot_details.id) > 0
          )
          SELECT
            *,
            CAST(appointment_date + slot_time_start AS TIMESTAMPTZ) AT TIME ZONE 'GMT' AS slot_start,
            CAST(appointment_date + slot_time_end AS TIMESTAMPTZ) AT TIME ZONE 'GMT' AS slot_end
          FROM
            next_appointments
          WHERE
            NOT EXISTS (
                SELECT 1
                FROM closed_dates
                WHERE (CAST(appointment_date + slot_time_start AS TIMESTAMPTZ) AT TIME ZONE 'GMT', CAST(appointment_date + slot_time_end AS TIMESTAMPTZ) AT TIME ZONE 'GMT') OVERLAPS (closed_dates.start_datetime, closed_dates.end_datetime) AND
                      closed_dates.local_id = next_appointments.local_id AND
                      closed_dates.deleted_at IS NULL
            ) AND 
            appointment_date >= start_date 
        ) AS next_appointments
       WHERE
        slot_start > CURRENT_TIMESTAMP
      ORDER BY 
        slot_start
      LIMIT 1;

      IF NOT FOUND THEN
        RETURN fn2_next_available_appointment_for_user(id_user, id_local, CAST(start_date + INTERVAL '7 days' AS DATE), try_count + 1);
        EXIT;
      END IF;

      timeSlot := NULL;
      timeSlot.day_of_week := rec.day_of_week;
      timeSlot.detail_id := rec.detail_id;
      timeSlot.health_id := rec.health_plan_id;
      timeSlot.id := rec.id;
      timeSlot.local_id := rec.local_id;
      timeSlot.private := rec.private;
      timeSlot.queue_type := rec.queue_type;
      timeSlot.slot_count := rec.slot_count;
      timeSlot.slot_date := rec.appointment_date;
      timeSlot.slot_time_end := rec.slot_time_end;
      timeSlot.slot_time_start := rec.slot_time_start;
      timeSlot.slot_type := rec.slot_type;
      timeSlot.user_id := rec.user_id;

      RETURN fn2_convert_time_slot2_to_available_appointment2(timeSlot);      
    
    ELSE
      -- não tem id_user
      SELECT
        INTO rec *
      FROM
        (
          WITH next_appointments AS (
              SELECT
                time_slots.id,
                time_slots.local_id,
                time_slots.user_id,
                time_slots.day_of_week,
                time_slots.slot_time_start,
                time_slots.slot_time_end,
                time_slots.queue_type,
                time_slots.slot_type,
                time_slot_details.health_plan_id,
                time_slot_details.private,
                time_slot_details.slot_count,
                time_slot_details.id AS detail_id,
                (CASE
                 WHEN time_slots.slot_type = 1
                   THEN
                     CAST(
                         (
                           CASE
                           WHEN EXTRACT(DOW FROM start_date) <= time_slots.day_of_week
                             THEN
                               start_date - (EXTRACT(DOW FROM start_date) * '1 day' :: INTERVAL) +
                               (time_slots.day_of_week * '1 day' :: INTERVAL)
                           ELSE
                             start_date - (EXTRACT(DOW FROM start_date) * '1 day' :: INTERVAL) +
                             (time_slots.day_of_week * '1 day' :: INTERVAL) + (7 * '1 day' :: INTERVAL)
                           END
                         ) AS DATE
                     )
                 ELSE
                   time_slots.slot_date
                 END) AS appointment_date,
                fn2_check_available_slot_count(time_slot_details.id) AS free
              FROM
                time_slots,
                time_slot_details
              WHERE
                time_slots.id = time_slot_details.time_slot_id AND
                time_slots.local_id = id_local AND
                time_slots.deleted_at IS NULL AND
                fn2_check_available_slot_count(time_slot_details.id) > 0
          )
          SELECT
            *,
            CAST(appointment_date + slot_time_start AS TIMESTAMPTZ) AT TIME ZONE 'GMT' AS slot_start,
            CAST(appointment_date + slot_time_end AS TIMESTAMPTZ) AT TIME ZONE 'GMT' AS slot_end
          FROM
            next_appointments
          WHERE
            NOT EXISTS (
                SELECT 1
                FROM closed_dates
                WHERE (CAST(appointment_date + slot_time_start AS TIMESTAMPTZ) AT TIME ZONE 'GMT', CAST(appointment_date + slot_time_end AS TIMESTAMPTZ) AT TIME ZONE 'GMT') OVERLAPS (closed_dates.start_datetime, closed_dates.end_datetime) AND
                      closed_dates.local_id = next_appointments.local_id AND
                      closed_dates.deleted_at IS NULL
            ) AND appointment_date >= start_date 
        ) AS next_appointments
      WHERE
        slot_start > CURRENT_TIMESTAMP
      ORDER BY 
        slot_start
      LIMIT 1;

      IF NOT FOUND THEN
        RETURN fn2_next_available_appointment_for_user(id_user, id_local, CAST(start_date + INTERVAL '7 days' AS DATE), try_count + 1);
        EXIT;
      END IF;

      timeSlot := NULL;
      timeSlot.day_of_week := rec.day_of_week;
      timeSlot.detail_id := rec.detail_id;
      timeSlot.health_id := rec.health_plan_id;
      timeSlot.id := rec.id;
      timeSlot.local_id := rec.local_id;
      timeSlot.private := rec.private;
      timeSlot.queue_type := rec.queue_type;
      timeSlot.slot_count := rec.slot_count;
      timeSlot.slot_date := rec.appointment_date;
      timeSlot.slot_time_end := rec.slot_time_end;
      timeSlot.slot_time_start := rec.slot_time_start;
      timeSlot.slot_type := rec.slot_type;
      timeSlot.user_id := rec.user_id;

      RETURN fn2_convert_time_slot2_to_available_appointment2(timeSlot);      
    END IF;

  ELSE

    RETURN NULL;

  END IF;

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
        DB::statement("DROP FUNCTION IF EXISTS fn2_next_available_appointment_for_user(integer, integer, DATE, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_next_availables_appointments_for_user(INTEGER, INTEGER, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_next_availables_appointments_for_user(INTEGER, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_convert_time_slot2_to_available_appointment2(timeSlot time_slot2);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_next_availables_appointments_slots_for_user(INTEGER, INTEGER, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_next_availables_appointments_slots_for_user(INTEGER, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_parse_time_slot( INTEGER,  BOOLEAN,  INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_available_appointments_slots_for_date(DATE, INTEGER, BOOLEAN, INTEGER);");

        DB::statement("DROP FUNCTION IF EXISTS fn2_check_available_slot_count(INTEGER);");

        DB::statement("DROP TYPE IF EXISTS available_appointment2;");

        DB::statement("DROP TYPE IF EXISTS time_slot2;");

    }
}
