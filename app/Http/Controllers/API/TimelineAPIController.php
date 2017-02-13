<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 11/08/16
 * Time: 19:35
 */


namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\TimelineAPIRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use DB;

use App\Models\Local;

class TimelineAPIController extends AppBaseController {

    public function showTimeline(TimelineAPIRequest $request, $response = true) {

        $lat          = $request->has('lat')          ? $request->get('lat')          : -30.0404303;
        $lng          = $request->has('lng')          ? $request->get('lng')          : -51.1846945;
        $max_price    = $request->has('max_price')    ? $request->get('max_price')    : 9999999;
        $userId       = $request->has('id')           ? $request->get('id')           : -1;
        $offset       = $request->has('offset')       ? $request->get('offset')       : 0;
        $limit        = $request->has('limit')        ? $request->get('limit')        : 8;
        $maxDistanceInMeters = $request->has('distance') ? $request->get('distance')  : 9999999;

      $sql = "
WITH result AS (
    SELECT
      locals.id                                                                                      AS id,
      users.name                                                                                     AS title,
      users.preferred_user,
      users.image_src,
      locals.user_id,
      locals.amount,
      string_agg(specializations.name, ', ')                                                         AS subtitle,
      locals.name                                                                                    AS user_local,
      round(CAST((ST_Distance(t.x, location) / 1000.0) AS NUMERIC), 1)                               AS distance,";

      if($request->has('for_date')) {
        $date = Carbon::createFromTimestampUTC($request->get('for_date'));
        $sql .= " fn2_next_available_appointment_for_user($userId, locals.id, '" . $date->format('Y-m-d') . "') AS next_appointment ";
      } else {
        $minDate = null;
        $maxDate = null;

        if ($request->has('max_date') || $request->has('min_date')) {
          $minDate = $request->has('min_date') ? Carbon::createFromTimestampUTC($request->get('min_date')) : null;
          $maxDate = $request->has('max_date') ? Carbon::createFromTimestampUTC($request->get('max_date')) : null;
        }
        // (result.next_appointment).time_epoch BETWEEN 1479801600 AND 1479803400

        if($minDate != null) {
          $sql .= " fn2_next_available_appointment_for_user($userId, locals.id, '" . $minDate->format('Y-m-d H:i:s') . "') AS next_appointment ";
        } else {
          $sql .= " fn2_next_available_appointment_for_user($userId, locals.id) AS next_appointment ";
        }
      }

      $sql .= "
    FROM
      users,
      locals
      LEFT JOIN specializations ON specializations.id IN (SELECT local_specialization.specialization_id
                                                          FROM local_specialization
                                                          WHERE local_specialization.local_id = locals.id)
      CROSS JOIN (SELECT ST_GeomFromText('POINT($lng $lat)', 4326)) AS t(x)
    WHERE
      users.id = locals.user_id AND
      users.approval_status = 2 AND
      users.deleted_at IS NULL AND
      ST_Distance(t.x, location) < $maxDistanceInMeters AND
      locals.deleted_at IS NULL";

      if ($request->has('specializations')) {
        $specializations = $request->get('specializations');
        $ids = implode(",", $specializations);

        $sql .= " AND specializations.id IN ($ids) ";
      }

      if ($request->has('exams')) {
        $exams = $request->get('exams');

        $ids = implode(",", $exams);
        $sql .= " AND locals.id IN (SELECT exam_local.local_id FROM exam_local WHERE exam_local.exam_id IN ($ids)) ";
      }

      if ($request->has('health_plans')) {
        $healthPlans = $request->get('health_plans');

        $ids = implode(",", $healthPlans);
        $sql .= " AND locals.id IN (SELECT health_plan_local.local_id FROM health_plan_local WHERE health_plan_local.health_plan_id IN ($ids)) ";
      }

      if ($request->has('max_amount')) {
        $maxAmount = $request->get('max_amount');

        $sql .= " AND (locals.amount IS NULL OR locals.amount <= $maxAmount) ";
      }

      if($request->has('max_distance')){
        $max_distance = $request->get('max_distance');

        $sql .= " AND round(CAST((ST_Distance(t.x, location) / 1000.0) AS NUMERIC), 1) <= round(CAST(($max_distance / 1000.0) AS NUMERIC), 1)";
      }

      if ($request->has("term")) {
        $term = $request->get("term");

        $sql .= " AND (
            users.name ~* '$term' OR
            locals.id IN (SELECT exam_local.local_id FROM exam_local WHERE exam_local.exam_id IN (SELECT exams.id FROM exams WHERE exams.name ~* '$term')) OR
            locals.id IN (SELECT health_plan_local.local_id FROM health_plan_local WHERE health_plan_local.health_plan_id IN (SELECT health_plans.id FROM health_plans WHERE health_plans.name ~* '$term')) OR
            locals.name ~* '$term' OR
            locals.address ~* '$term' OR
            specializations.name ~* '$term'
            ) ";
      }

      $sql .= "
    GROUP BY
      locals.id, locals.user_id, users.name, users.image_src, t.x, users.preferred_user
)
SELECT
  result.id,
  result.title,
  result.preferred_user,
  result.user_id,
  (CASE WHEN result.image_src IS NOT NULL AND result.image_src <> '' THEN result.image_src ELSE '".url('/')."/assets/site/images/img_medicos.jpg' END) AS image_url,
  COALESCE(result.subtitle, '') AS subtitle,
  result.user_local AS user_local,
  'R$ ' || result.amount || ',00' AS amount_str,
  CAST(result.distance || ' Km' AS VARCHAR(20)) AS distance_str,
  (result.next_appointment).*,
  (result.next_appointment).time_header AS header_title,
  SUBSTR((result.next_appointment).time_label, 1, 5) AS time_str
FROM 
    result
WHERE
    (result.next_appointment).time_slot_id > 0    
";

      // amount_str guga
      // COALESCE((CASE WHEN (result.next_appointment).time_slot_for_queue THEN '' ELSE CAST(CAST(result.amount AS NUMERIC) AS money) END), '') AS amount_str,


      if(isset($maxDate)) {
        $sql .= " AND CAST(to_timestamp((result.next_appointment).time_epoch) AS DATE) <= '" . $maxDate->format("Y-m-d H:i:s") . "' ";
      }

      $sqlByDistance = $sql . " 
ORDER BY
  result.preferred_user, result.distance, (result.next_appointment).time_epoch 
LIMIT $limit 
OFFSET $offset;";

      $sqlByDate = $sql . " 
ORDER BY 
  result.preferred_user, (result.next_appointment).time_epoch, result.distance 
LIMIT $limit 
OFFSET $offset;";

//        $sqls = [
//            "locals_by_date" => $sqlByDate,
//            "locals_by_distance" => $sqlByDistance
//        ];
//
//        return response()->json($sqls);

        DB::statement("SET LC_TIME = 'pt_BR.UTF8'");
        DB::statement("SET LC_MONETARY = 'pt_BR.UTF8';");

        $byDate = DB::select(DB::raw($sqlByDate));
        $byDistance = DB::select(DB::raw($sqlByDistance));

        $jsonData = [
            "locals_by_date" => $byDate,
            "locals_by_distance" => $byDistance
        ];

        return $response ? response()->json($jsonData) : $jsonData;
    }
    
}
