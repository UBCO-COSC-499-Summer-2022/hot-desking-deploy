<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function download(Request $request)
    {
        // $file = Storage::path('storage/app/Email-Logs.txt');
        return Storage::download('Email-Logs.txt');
    }
}
