<?php

use App\Models\Admin\Options;
use App\Models\Admin\Menu;
use Illuminate\Database\Seeder;

// PostGis
use Phaza\LaravelPostgis\Geometries\Point;
use Carbon\Carbon;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Cria o primeiro usuario
     */
    public function createAdmin() {
        if(User::where('email', 'suporte@admin.com')->count() == 0) {
            $admin              = new User();
            $admin->name        = "Administrador";
            $admin->email       = "suporte@admin.com";
            $admin->password    = bcrypt("admin");
            $admin->user_type   = User::UserTypeAdmin;
            $admin->save();
        }
    }

    /**
     * Gera os menus para Usuario Administrador que e o Desenvlvedor
     */
    public function generateAdminDevMenus() {
        Menu::create([
            'menu'       => 'Home',
            'icon'       => 'fa fa-home',
            'active'     => 'home/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '0',
            'link_to'    => 'home'
        ]);

        Menu::create([
            'menu'       => 'Dashboard',
            'icon'       => 'fa fa-dashboard',
            'active'     => 'dashboard/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '1',
            'link_to'    => 'dashboard'
        ]);

        Menu::create([
            'menu'       => 'Ambiente',
            'icon'       => 'fa fa-tree',
            'active'     => 'decompose/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '2',
            'link_to'    => 'decompose'
        ]);

        Menu::create([
            'menu'       => 'Rotas',
            'icon'       => 'fa fa-map',
            'active'     => 'routes/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '3',
            'link_to'    => 'routes'
        ]);

        Menu::create([
            'menu'       => 'Env Editor',
            'icon'       => 'fa fa-code',
            'active'     => 'enveditor/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '4',
            'link_to'    => 'enveditor'
        ]);

        Menu::create([
            'menu'       => 'Logs',
            'icon'       => 'fa fa-pencil-square',
            'active'     => 'logs/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '5',
            'link_to'    => 'logs'
        ]);

        Menu::create([
            'menu'       => 'APIs',
            'icon'       => 'fa fa-code',
            'active'     => 'api/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '6',
            'link_to'    => 'api'
        ]);

        Menu::create([
            'menu'       => 'Usuarios',
            'icon'       => 'fa fa-users',
            'active'     => 'users/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '7',
            'link_to'    => 'users'
        ]);

        Menu::create([
            'menu'       => 'Paginas',
            'icon'       => 'fa fa-file-o',
            'active'     => 'pages/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '8',
            'link_to'    => 'pages'
        ]);

        Menu::create([
            'menu'       => 'Opçoes',
            'icon'       => 'fa fa-list-ul',
            'active'     => 'options/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '9',
            'link_to'    => 'options'
        ]);

        Menu::create([
            'menu'       => 'Menus',
            'icon'       => 'fa fa-bars',
            'active'     => 'menus/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '10',
            'link_to'    => 'menus'
        ]);

        Menu::create([
            'menu'       => 'Holder',
            'icon'       => 'fa fa-home',
            'active'     => 'holder/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '11',
            'link_to'    => 'holder'
        ]);

        Menu::create([
            'menu'       => 'Strings',
            'icon'       => 'fa fa-font',
            'active'     => 'strings/*',
            'menu_root'  => null,
            'appears_to' => User::UserTypeAdmin,
            'order'      => '12',
            'link_to'    => 'strings'
        ]);
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
        $this->generateAdminDevMenus();

        \Illuminate\Database\Eloquent\Model::reguard();
    }
}
