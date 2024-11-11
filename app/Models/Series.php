<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Series extends Model
{
  protected $guarded = ['id'];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function status()
  {
    return $this->belongsTo(Status::class);
  }

  public function episodes()
  {
    return $this->hasMany(Episode::class);
  }

  public function latestEpisode()
  {
    return $this->hasOne(Episode::class)->orderBy('number', 'desc');
  }
}
