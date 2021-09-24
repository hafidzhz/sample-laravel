<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function edit($id)
    {
        $user = UserRepository::getData($id);
        return view('edit', compact(
            'user'
        ));
    }

    public function add()
    {
        return view('edit');
    }
}
