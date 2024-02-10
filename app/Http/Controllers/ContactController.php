<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list()
    {

        return response()->json(Contact::getContactsFromUser(Auth::user()->id), 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $isValid = Contact::contactIsValid($input);
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }
        $new_contact = Contact::createAndSyncContact($input, Auth::user());
        return response()->json($new_contact, 201);
    }

    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $isValid = Contact::contactIsValid($input);
        if (!$isValid->isValid) {
            return response()->json($isValid, 400);
        }

        //TODO: Move to Contact model
        //TODO:: check if contact is not contact for user logged-in
        $contact = Contact::find($id);

        $res = Contact::updateContact($contact, $input);
        return response()->json($res, 200);
    }
}
