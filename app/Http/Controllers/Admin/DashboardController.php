<?php

namespace App\Http\Controllers\Admin;

use DB;
use File;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $diskUsage       = $this->getDiskUsage();
        $diskTotal       = $this->getTotalDisk();
        $databaseSize    = $this->getDatabaseSize();
        $memoryAvailable = $this->getMemoryAvailable();
        $totalMemory     = $this->getTotalMemory();
        $directories     = $this->getDirectoryTree();
        $existZip        = $this->checkZip();

        return view('admin.dashboard.index', compact('diskUsage', 'diskTotal', 'databaseSize', 'memoryAvailable', 'totalMemory', 'existZip'));
    }

    /**
     * @return bool
     */
    public function checkZip()
    {
        $exists = shell_exec("cd .. && ls public/zip");

        if ( $exists != "" ) return true;

        return false;
    }

    /**
     * @return string
     */
    public function getDiskUsage()
    {
        $diskUsage = shell_exec("cd .. && du -sh");
        $diskUsage = str_replace("\t.\n", "", $diskUsage) . "b";

        return $diskUsage;
    }

    /**
     * @return string
     */
    public function getTotalDisk()
    {
        $diskTotal = str_replace("\n", "", shell_exec("df -h | grep /dev/sda3 | awk '{print $2}'"))."b";

        return $diskTotal;
    }

    /**
     * @return string
     */
    public function getDatabaseSize()
    {
        $databaseSize = DB::raw(DB::select("select pg_database_size('".getenv("DB_DATABASE")."')"))->getValue()[0]->pg_database_size;
        $databaseSize = number_format($databaseSize / 1048576, 2) . "Mb";

        return $databaseSize;
    }

    /**
     * @return string
     */
    public function getMemoryAvailable()
    {
        $memoryAvailable = shell_exec("grep MemAvailable /proc/meminfo | awk '{print $2}'");
        $memoryAvailable = number_format($memoryAvailable / 1048576, 2);
        $memoryAvailable = $memoryAvailable < 1 ? ( $memoryAvailable . "Mb" ) : ($memoryAvailable . "Gb");

        return $memoryAvailable;
    }

    /**
     * @return string
     */
    public function getTotalMemory()
    {
        $totalMemory = shell_exec("grep MemTotal /proc/meminfo | awk '{print $2}'");
        $totalMemory = number_format($totalMemory / 1048576, 2);
        $totalMemory = $totalMemory < 1 ? ( $totalMemory . "Mb" ) : ($totalMemory . "Gb");

        return $totalMemory;
    }

    /**
     * @return array
     */
    public function getDirectoryTree()
    {
        $files = shell_exec("cd .. && ls");
        $files = explode("\n", $files);
        array_pop($files);

        $tree = [];

        $i = 0;

        foreach ( $files as $file ){
            if ( File::isDirectory("../$file") ){
                $tree[$i]["name"] = $file;
                $tree[$i]["size"] = $this->getDirUsage($file);
            }else{
                $tree[$i]["name"] = $file;
                $tree[$i]["size"] = $this->getFileSize($file);
            }

            $i++;
        }

        return $tree;
    }

    /**
     * @param $dir
     * @return string
     */
    public function getDirUsage($dir)
    {
        $diskUsage = shell_exec("cd ../$dir && du -sh");
        $diskUsage = str_replace("\t.\n", "", $diskUsage) . "b";

        return $diskUsage;
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileSize($file)
    {
        $size = shell_exec("cd .. && ls -l $file | awk '{print $5}'");
        $size = number_format($size / 1024, 2) . "Kb";

        return $size;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function prepareZip()
    {
        $zipName = strtolower(getenv("TITLE")).".zip";

        shell_exec("cd .. && zip -r $zipName . && mv $zipName public/zip");

        \Flash::success("ZIP gerado!");

        return redirect(url("/admin/dashboard"));
    }
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function prepareZipVendor()
    {
        $zipName = strtolower( getenv( "TITLE" ) ).".zip";

        shell_exec("cd .. && zip -r $zipName app bootstrap config database public resources storage tests .env .gitignore artisan composer.json composer.lock gulpfile.js infyom_json.json LICENSE package.json phpunit.xml README.md readme.md server.php && mv $zipName public/zip");

        \Flash::success("ZIP gerado!");

        return redirect(url("/admin/dashboard"));
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteZip()
    {
        $zipName = strtolower(getenv("TITLE")).".zip";

        shell_exec("cd ../public/zip && rm -rf $zipName");

        \Flash::success("ZIP deletado!");

        return redirect(url("/admin/dashboard"));
    }
}
