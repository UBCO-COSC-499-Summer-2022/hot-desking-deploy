<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class EmailLogsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin', 'isSuspended']);
    }

    public function index()
    {   
        return view('admin.emailLogs');
    }

    public function downloadLogs()
    {   
        $storageDestPath = public_path('EmailLogs.csv'); 
        if(!Storage::exists($storageDestPath)){
            Session::flash('message', 'No logs available.');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->route('emailLogs');
        } else {
            $fileSize = Storage::size($storageDestPath);
            // dd($fileSize);
            if($fileSize > 100000000)
            {
                $dt = Carbon::now();
                echo $dt->toDateString();
                $rolloverFile = "EmailLogs_" . $dt . ".csv"; 
                Storage::copy($storageDestPath, 'old/'.$rolloverFile);
                return Storage::download('old/'.$rolloverFile);
            } else {
                return Storage::download($storageDestPath);
            }
        }
        
        
    }
}
