<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedMarker extends Model
{
	protected $date = [
		'end_date', 
		'start_date'
	];
	protected $table = 'ordered_markers';
	protected $fillable = [
		'marker_id', 'user_id', 'status', 'uniqid', 'end_date', 'start_date'
	];
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
    public function marker()
	{
		return $this->belongsTo('App\Marker');
	}
	
}