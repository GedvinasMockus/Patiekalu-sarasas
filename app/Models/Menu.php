<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $restaurant
 * 
 * @property Collection|Dish[] $dishes
 *
 * @package App\Models
 */
class Menu extends Model
{
	protected $table = 'menu';
	public $timestamps = false;

	protected $casts = [
		'restaurant' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'restaurant'
	];

	public function restaurant()
	{
		return $this->belongsTo(Restaurant::class, 'restaurant');
	}

	public function dishes()
	{
		return $this->hasMany(Dish::class, 'menu');
	}
}
