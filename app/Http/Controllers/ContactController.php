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

    public function list(Request $request)
    {

        $value = $request->query('filter');
        $id = $request->query('id');
        $column = 'name';

        if ($id) {
            $column = 'id';
            $value = $id;
        }
        return response()->json(Contact::getContactsFromUser(Auth::user()->id, $column, $value), 200);
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

        $contact = Contact::getContactById($id);

        $res = Contact::updateContact($contact, $input);
        return response()->json($res, 200);
    }
}
