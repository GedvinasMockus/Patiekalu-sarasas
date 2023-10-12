<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
class User extends Model
{
	protected $table = 'user';
	public $timestamps = false;

	protected $casts = [
		'role' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'surname',
		'email',
		'password',
		'role'
	];

	public function user_role()
	{
		return $this->belongsTo(UserRole::class, 'role');
	}

	public function restaurants()
	{
		return $this->hasMany(Restaurant::class, 'owner');
	}
}
