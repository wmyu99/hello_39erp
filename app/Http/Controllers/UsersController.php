<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class UsersController extends Controller
{
    public function create()
    {
    	return view('users.create');
    }
}
