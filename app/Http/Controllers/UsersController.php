<?php

namespace App\Http\Controllers;

use App\Enums\UserRuleType;
use Illuminate\Http\Request;
use App\Models\User;
use stdClass;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json(User::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $existUser = User::where('email', $input["email"])->first();
        if ($existUser) {
            return response()->json(json_decode('{"error":"Already exist an user with that email"}'), 400);
        }

        $isValid = User::userIsValid($input, 'create');
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }

        $new_user = User::create($input);
        return response()->json($new_user, 201);
    }

    static function getUserByKey(string $key, string $value)
    {
        $res = new stdClass;
        $res->error = null;
        if (!$value) {
            $res->error = '{"error": "' + $key + ' is required"}';
            $res->errorCode = 400;
        }

        $res->value = User::where($key, $value)->first();

        if (!$res->value) {
            $res->error = '{"error": "User not found"}';
            $res->errorCode = 404;
        }
        return $res;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->getUserByKey("id", $id);
        if ($response->error) {
            return response()->json(json_decode($response->error), $response->errorCode);
        }

        return response()->json($response->value, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $response = $this->getUserByKey("id", $id);
        if ($response->error) {
            return response()->json(json_decode($response->error), $response->errorCode);
        }
        $user = $response->value;

        $input = $request->all();
        $isValid = User::userIsValid($input, 'update');
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }

        foreach (array_keys($input) as $val) {
            $user[$val] = $input[$val];
        }
        $user->save();
        return response()->json($user, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->getUserByKey("id", $id);
        if ($response->error) {
            return response()->json(json_decode($response->error), $response->errorCode);
        }
        $user = $response->value;
        $user->delete();
        return response()->json($user, 200);
    }
}
