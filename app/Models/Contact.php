<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use stdClass;

class Contact extends Model
{
    use HasFactory, Notifiable, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'phone',
        'title',
        'description',
        'address',
        'email',
        'profile_picture',
        'banner_picture',
        'user_id'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    static function rules()
    {
        return [
            'name' => 'string',
            'phone' => 'string|nullable',
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'address' => 'string|nullable',
            'email' => 'string|nullable',
            'profile_picture' => 'string|nullable',
            'banner_picture' => 'string|nullable'
        ];
    }

    static function getContactsFromUser($userId, $column, $value)
    {
        if (!$userId)
            return [];

        return Contact::where('user_id', $userId)->where($column, 'like', '%' . $value . '%')->paginate(9);
    }
    static function contactIsValid($contact)
    {
        $validator = Validator::make($contact, Contact::rules());
        $object = new stdClass;
        $object->isValid = !$validator->fails();
        $object->errors = $validator->errors();
        return $object;
    }

    static function createAndSyncContact($contact, $user)
    {
        $contact['user_id'] = $user->id;
        return Contact::create($contact);
        // error_log(json_decode($new_contact)->id);
        // return Contact::where('id', json_decode($new_contact)->id);
    }

    static function updateContact($contact, $data)
    {
        foreach (array_keys($data) as $val) {
            $contact[$val] = $data[$val];
        }
        $contact->save();
        return $contact;
    }
}
