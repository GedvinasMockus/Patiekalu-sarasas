<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Restaurant
 * 
 * @property int $id
 * @property string $name
 * @property string $status
 * @property int $owner
 * 
 * @property User $user
 * @property Collection|Menu[] $menus
 *
 * @package App\Models
 */
class Restaurant extends Model
{
	use HasFactory;
	protected $table = 'restaurant';
	public $timestamps = false;

	protected $casts = [
		'owner' => 'int'
	];

	protected $fillable = [
		'name',
		'status',
		'owner'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'owner');
	}

	public function menus()
	{
		return $this->hasMany(Menu::class, 'restaurant');
	}
}
