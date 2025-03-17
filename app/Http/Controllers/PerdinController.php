<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Request;
use App\Models\User;

class PerdinController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }
    public function index()
    {
        $id = session('user_id');
        $jmlperjalan = TravelRequest::where('user_id', $id)->count();
        return view('user.perdin.index', ['name' => $this->name, 'jumlah' => $jmlperjalan]);
    }
}
