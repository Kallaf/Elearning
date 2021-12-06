<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;
use Ellaisys\Cognito\Auth\RegistersUsers;

class InstructorsController extends Controller
{
    use RegistersUsers;

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $instructor = new Instructor;
        $instructor->name = $request->username;
        $instructor->email = $request->email;
        $instructor->userId = "1234";
        $instructor->save();
        return $instructor;
    }
}
