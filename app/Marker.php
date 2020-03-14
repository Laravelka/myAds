<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
	protected $table = 'markers';
	protected $dates = ['start_date', 'end_date'];
	
	public function orderedMarker()
	{
		return $this->hasOne('App\OrderedMarker');
	}
}
