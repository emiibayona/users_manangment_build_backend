<?php

namespace App\Http\Controllers;

use App\Enums\UserRuleType;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use stdClass;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'store', 'index']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::getAll(), 200);
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

        $isValid = User::userIsValid($input, true);
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }

        $new_user = User::create($input);
        return response()->json($new_user, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = User::getUserByKey("id", $id);
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
        $response = User::getUserByKey("id", $id);
        if ($response->error) {
            return response()->json(json_decode($response->error), $response->errorCode);
        }
        $user = $response->value;

        $input = $request->all();
        $isValid = User::userIsValid($input, false);
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }

        return response()->json(User::updateUser($user, $input), 200);

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

        if (Auth::user()->id === $id) {
            //Info: Role system not implemented in this version.
            return response()->json(User::deleteUser($response->value), 200);
        } else
            return response(['error' => 'You cant delete another user'], 400);
    }
}
