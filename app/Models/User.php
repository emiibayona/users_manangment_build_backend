<?php

namespace App\Models;


use App\Enums\UserRuleType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use stdClass;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'activity',
        'description',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    static function userIsValid($user, $type)
    {
        $validator = Validator::make($user, User::rules($type));
        $object = new stdClass;
        $object->isValid = !$validator->fails();
        $object->errors = $validator->errors();
        return $object;

    }

    static function rules(string $type = 'create')
    {
        $create_rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string',

        ];
        $update_rules = [
            'phone' => 'string|nullable',
            'activity' => 'string|nullable',
            'description' => 'string|nullable',
            'address' => 'string|nullable'
        ];
        $delete_rules = [];

        if ($type === 'create') {
            return [...$create_rules, ...$update_rules];
        } else if ($type === 'update') {
            return $update_rules;
        } else if ($type === 'delete') {
            return $delete_rules;
        }

    }
}
