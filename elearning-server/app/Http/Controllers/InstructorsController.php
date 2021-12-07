<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;
use BlackBits\LaravelCognitoAuth\CognitoClient;

class InstructorsController extends Controller
{

    public function register(Request $request, CognitoClient $cognitoClient)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $attributes = [];

        $userFields = ['name', 'email'];

        foreach($userFields as $userField) {

            if ($request->$userField === null) {
                throw new \Exception("The configured user field $userField is not provided in the request.");
            }

            $attributes[$userField] = $request->$userField;
        }

        $result = $cognitoClient->register($request->email, $request->password, $attributes);

        if ($result) {
            $instructor = new Instructor;
            $instructor->name = $request->name;
            $instructor->email = $request->email;
            $instructor->userId = $result['UserSub'];
            $instructor->save();
            return $instructor;
        } else {
            return "Email already exists";
        }
    }
}
