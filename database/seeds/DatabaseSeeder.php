<?php

use Illuminate\Database\Seeder;

// PostGis
use Phaza\LaravelPostgis\Geometries\Point;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\ClosedDate;
use App\Models\Exam;
use App\Models\HealthPlan;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Local;
use App\Models\TimeSlot;
use App\Models\TimeSlotDetail;

class DatabaseSeeder extends Seeder
{

    public function createAdmin() {
        if(User::where('email', 'suporte@aioria.com.br')->count() == 0) {
            $admin = new User();
            $admin->name = "Administrador";
            $admin->email = "suporte@aioria.com.br";
            $admin->password = bcrypt("aioria");
            $admin->user_type = 1;
            $admin->save();
        }
    }

    public function createSpecializations() {
        if(Specialization::all()->count() == 0) {
            $specializations = [
                ["name" => "Acupuntura", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Alergia e Imunologia", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Anestesiologia", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Angiologia", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Cancerologia", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Cardiologia", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Cirurgia Cardiovascular", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
                ["name" => "Cirurgia da Mão", "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()],
            ];

            Specialization::insert($specializations);
        }
    }

    public function createExams() {
        if(Exam::all()->count() == 0) {
            $exams = [
                ["name" => "Abdominoscopia"],
                ["name" => "Abreugrafia"],
                ["name" => "Adenograma"],
                ["name" => "Antibiograma"],
                ["name" => "Anuscopia"],
                ["name" => "Aortografia"],

                ["name" => "Biopsia"],

                ["name" => "Calcemia"],
                ["name" => "Capnometria"],
                ["name" => "Carga viral"],
                ["name" => "Cartão de Guthrie"],
                ["name" => "Cateterismo"],
                ["name" => "Cistocentese"],
                ["name" => "Citometria de fluxo"],
                ["name" => "Coagulograma"],
                ["name" => "Colonoscopia"],
                ["name" => "Coloração diferencial"],

                ["name" => "Densitometria óssea"],
                ["name" => "Doença residual mínima"],
                ["name" => "Doppler transcraniano"],

                ["name" => "Ecodoppler"],
                ["name" => "Eletronistagmografia"],
                ["name" => "ELISA"],
                ["name" => "Escore de Bishop"],
                ["name" => "Esofagografia"],
                ["name" => "Esofagograma"],
                ["name" => "Espéculo"],
                ["name" => "Espermograma"],
                ["name" => "Espirometria"],
                ["name" => "Estetoscópio"],
                ["name" => "Exame pré-nupcial"],

                ["name" => "Falso positivo"],

                ["name" => "Gasometria arterial"],
                ["name" => "Glicosímetro"],
                ["name" => "Glicosúria"],

                ["name" => "Hemadsorção"],
                ["name" => "Hemograma"],
                ["name" => "Histerosalpingografia"],

                ["name" => "Imagiologia médica"],
                ["name" => "Imuno-histoquímica"],
                ["name" => "Imunofenotipagem"],

                ["name" => "Laringoscopia"],
                ["name" => "Lesão intraepitelial de alto grau"],

                ["name" => "Magnetoencefalografia"],
                ["name" => "Manobra de Phalen"],
                ["name" => "Mielografia"],
                ["name" => "Mielograma"],
                ["name" => "MRI de difusão"],

                ["name" => "Nasofaringoscopia"],
                ["name" => "Nasofibrolaringoscopia"],
                ["name" => "Nasofibroscopia"],
                ["name" => "Neurofeedback"],
                ["name" => "Neuroimagiologia"],

                ["name" => "Perfil lipídico"],
                ["name" => "Potencial evocado"],
                ["name" => "Pressão venosa central"],
                ["name" => "Prova cruzada"],
                ["name" => "Punção aspirativa por agulha fina"],
                ["name" => "Punção lombar"],

                ["name" => "Reação de Agostini"],
                ["name" => "Reação de Paul-Bunnell-Davidsohn"],
                ["name" => "Reação intradérmica"],
                ["name" => "Retossigmoidoscopia"],

                ["name" => "Sigmoidoscopia"],

                ["name" => "Teste de Allen"],
                ["name" => "Teste de Coombs"],
                ["name" => "Teste de ácido nucleico"],
                ["name" => "Teste de Ham"],
                ["name" => "Teste de Dix-Hallpike"],
                ["name" => "Teste do suor"],
                ["name" => "Teste de gravidez"],
                ["name" => "Teste de inclinação ortostática"],
                ["name" => "Teste do nitroazul de tetrazólio"],
                ["name" => "Teste de Papanicolau"],
                ["name" => "Teste do pezinho"],
                ["name" => "Teste VDRL"],
                ["name" => "Tipagem sanguínea"],
                ["name" => "Tonometria"],
                ["name" => "Toracocentese"],

                ["name" => "Uranálise"],
                ["name" => "Urocultura"],
                ["name" => "Urografia"],

                ["name" => "Vectoeletronistagmografia"],
                ["name" => "Velocidade de hemossedimentação"],
                ["name" => "Vitroscopia"],

                ["name" => "Xenodiagnóstico"],
            ];

            Exam::insert($exams);
        }
    }

    public function createHealthPlan() {
        if(HealthPlan::all()->count() == 0) {
            $health_plans = [
                ["name" => "Unimed"],
                ["name" => "SulAmérica"],
                ["name" => "Bradesco"],
                ["name" => "Amil"],
                ["name" => "Doctor Clin"]
            ];

            HealthPlan::insert($health_plans);
        }
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        \Illuminate\Database\Eloquent\Model::unguard();

        $this->createAdmin();

        $this->createSpecializations();

        $this->createExams();

        $this->createHealthPlan();

        $this->registrarMedico(
            ['name' => 'Gustavo Tagliari Filho', 'email' => 'gustavo@aioria.com.br', 'phone' => '', 'password' => 'aioria', 'image_src' => 'assets/site/images/img_medicos.jpg'],
            ['name' => 'Santa Casa de Misericórdia de Porto Alegre', 'address' => 'Rua Professor Annes Dias, 295, Centro Histórico', 'amount' => 120, 'duration' => 30, 'lat' => -30, 'lng' => -51]
        );

        \Illuminate\Database\Eloquent\Model::reguard();
    }
    
    function registrarMedico($userData, $hospitalData) {

        $email = $userData['email'];

        if(User::where('email', $email)->count() > 0) {
            return;
        }

        $localId = -1;
        $medicoId = -1;

        $medico = new User;
        $medico->name = $userData['name'];
        $medico->email = $email;
        $medico->phone = $userData['phone'];
        $medico->password = bcrypt($userData['password']);

        $medico->user_type = 2;

        if ($medico->save()) {
            $medicoId = $medico->id;
            $hospital = new Local;
            $hospital->name = $hospitalData['name'];
            $hospital->address = $hospitalData['address'];
            $hospital->amount = $hospitalData['amount'];
            $hospital->appointment_duration_in_minutes = $hospitalData['duration'];
            $hospital->location = new Point($hospitalData['lat'], $hospitalData['lng']);
            if ($medico->locals()->save($hospital)) {
                $localId = $hospital->id;
            }
        }

        if($localId == -1 || $medicoId == -1) {
            return;
        }

        $this->createAppointments($localId, $medicoId);
    }

    function generateRandomMedic($count) {
        $users = factory(User::class, $count)->make();
        foreach($users as $user) {
            $user->user_type = 2;
            if($user->save()) {
                $user->locals()->save(factory(Local::class)->make());
            }
        }
    }

    function generateAppointmentsForMedicsIn($ids_plain) {
        $medicos = User::whereIn("id", $ids_plain)->get();
        foreach ($medicos AS $medico) {
            foreach ($medico->locals AS $local) {
                $this->createAppointments($local->id, $medico->id);
            }
        }
    }

    function createAppointments($localId, $medicoId) {
        $healthPlans = HealthPlan::all();

        echo("manhãs");
        echo("\n\r");

        for($dayOfWeek = 2; $dayOfWeek <= 6; $dayOfWeek++) {
            $start = Carbon::createFromTime(8, 0, 0);

            echo("dayOfWeek = " . $dayOfWeek);
            echo("\n\r");

            while ($start->lt(Carbon::createFromTime(12, 0, 0))) {
                $end = $start->copy()->addMinutes(30);

                echo("start = $start end = $end");
                echo("\n\r");

                $timeSlotId = $this->createTimeSlot($localId, $medicoId, TimeSlot::TimeSlotDefault, $dayOfWeek, $start, $end, TimeSlot::TimeSlotTypeAppointment);

                if($timeSlotId) {
                    $private = random_int(0, 1);
                    $details = new TimeSlotDetail();

                    $details->private = $private;

                    if($private === 0) {
                        $max = sizeof($healthPlans);
                        if($max > 0) {
                            $pos = random_int(0, $max-1);

                            $details->healthPlan()->associate($healthPlans[$pos]);
                        }
                    }

                    $details->slot_count = 1;

                    $timeSlot = TimeSlot::find($timeSlotId);
                    $timeSlot->timeSlotDetails()->save($details);
                }

                $start = $start->addMinutes(30);
            }
        }

        echo("\n\r");
        echo("\n\r");
        echo("\n\r");
        echo("tardes");
        echo("\n\r");


        for($dayOfWeek = 2; $dayOfWeek <= 6; $dayOfWeek++) {
            echo("dayOfWeek = " . $dayOfWeek);
            echo("\n\r");

            $start = Carbon::createFromTime(14, 0, 0);
            $end = Carbon::createFromTime(20, 0, 0);

            echo("start = $start end = $end");
            echo("\n\r");

            $timeSlotId = $this->createTimeSlot($localId, $medicoId, TimeSlot::TimeSlotDefault, $dayOfWeek, $start, $end, TimeSlot::TimeSlotTypeQueue);

            if($timeSlotId) {
                $total_count = 20;

                $already_private = false;
                $pos = 0;

                while($total_count > 0) {
                    $total = 5;

                    echo("total = " . $total);
                    echo("\n\r");

                    $details = new TimeSlotDetail();

                    if ($already_private == false) {
                        $details->private = true;
                        $already_private = true;
                    } else {
                        $details->private = false;
                    }

                    echo("private = " . ($details->private ? "S" : "N"));
                    echo("\n\r");

                    if($details->private == false) {
                        $max = sizeof($healthPlans);
                        if($max > 0) {
                            $healthPlan = $healthPlans[$pos];
                            $pos++;

                            echo("healthPlan = (" . $healthPlan->id . ") " . $healthPlan->name);
                            echo("\n\r");

                            $details->healthPlan()->associate($healthPlan);
                        }
                    }

                    $details->slot_count = $total;

                    $total_count -= $total;

                    $timeSlot = TimeSlot::find($timeSlotId);
                    $timeSlot->timeSlotDetails()->save($details);

                }
            }
        }
    }

    function createTimeSlot($localId, $medicoId, $slotType, $dayOfWeek, $start, $end, $queueType) {
        $timeSlot = new TimeSlot();
        $timeSlot->local_id = $localId;
        $timeSlot->user_id = $medicoId;
        $timeSlot->slot_type = $slotType;
        $timeSlot->day_of_week = $dayOfWeek;
        $timeSlot->slot_time_start = $start;
        $timeSlot->slot_time_end = $end;
        $timeSlot->queue_type = $queueType;
        if($timeSlot->save()) {
            return $timeSlot->id;
        } else {
            return null;
        }
    }

}
