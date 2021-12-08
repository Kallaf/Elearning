<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;
use BlackBits\LaravelCognitoAuth\CognitoClient;
use Exception;

class InstructorsController extends Controller
{

    public function register(Request $request, CognitoClient $cognitoClient)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $instructor = Instructor::firstWhere('email', $request->email);
        if ($instructor) {
            return "Email already exists";
        }
        $attributes = [];

        $userFields = ['name', 'email'];

        foreach($userFields as $userField) {

            if ($request->$userField === null) {
                throw new \Exception("The configured user field $userField is not provided in the request.");
            }

            $attributes[$userField] = $request->$userField;
        }
        try {
            $cognitoClient->register(
                $request->email, $request->password, $attributes
            );
            $user = $cognitoClient->getUser($request->email);
            $userSub = collect($user['UserAttributes'])->where('Name', 'sub')->first->Value['Value'];
            $instructor = new Instructor;
            $instructor->name = $request->name;
            $instructor->email = $request->email;
            $instructor->userId = $userSub;
            $instructor->save();
            return $instructor;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCurrentInstructor(Request $request)
    {
        $instructor = Instructor::firstWhere('userId', $request->get('userId'));
        if ($instructor) {
            return $instructor;
        }
        return response("Instructor not found", 404);
    }
}
