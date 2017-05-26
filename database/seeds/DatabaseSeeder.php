<?php

use App\Models\Admin\Options;
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
        if(User::where('email', 'suporte@admin.com')->count() == 0) {
            $admin = new User();
            $admin->name = "Administrador";
            $admin->email = "suporte@admin.com";
            $admin->password = bcrypt("admin");
            $admin->user_type = 1;
            $admin->save();
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

        \Illuminate\Database\Eloquent\Model::reguard();
    }
}
