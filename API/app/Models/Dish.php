<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dish
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $picture
 * @property int $menu
 * 
 *
 * @package App\Models
 */
class Dish extends Model
{
	protected $table = 'dish';
	public $timestamps = false;

	protected $casts = [
		'menu' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'picture',
		'menu'
	];

	public function menu()
	{
		return $this->belongsTo(Menu::class, 'menu');
	}
}
