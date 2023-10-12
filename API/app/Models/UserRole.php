<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRole
 * 
 * @property int $id
 * @property string $role
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class UserRole extends Model
{
	protected $table = 'user_role';
	public $timestamps = false;

	protected $fillable = [
		'role'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'role');
	}
}
