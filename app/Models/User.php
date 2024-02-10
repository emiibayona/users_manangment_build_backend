<?php

namespace App\Models;


use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Enums\UserRuleType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use stdClass;

class User extends Authenticatable implements JWTSubject
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


    static function userIsValid($user, $checkPwd)
    {
        $validator = Validator::make($user, User::rules($checkPwd));
        $object = new stdClass;
        $object->isValid = !$validator->fails();
        $object->errors = $validator->errors();
        return $object;

    }

    static function rules(bool $checkPwd)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ];
        if ($checkPwd) {
            return [...$rules, 'password' => 'required|string'];
        }
        return $rules;
    }

    static function getAll()
    {
        return User::all();
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

    static function updateUser($user, $input)
    {
        foreach (array_keys($input) as $val) {
            $user[$val] = $input[$val];
        }
        $user->save();
        return $user;
    }

    static function deleteUser($user)
    {
        $user->delete();
        return $user;
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'name' => $this->name
        ];
    }
}
