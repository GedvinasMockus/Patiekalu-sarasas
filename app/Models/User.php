<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $password
 * @property int $role
 * 
 * @property UserRole $user_role
 * @property Collection|Restaurant[] $restaurants
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
	use HasApiTokens, HasFactory, Notifiable;
	protected $table = 'user';
	public $timestamps = false;

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $fillable = [
		'name',
		'surname',
		'email',
		'password',
		'role'
	];

	public function restaurants()
	{
		return $this->hasMany(Restaurant::class, 'owner');
	}

	public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}
