<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
  protected $guarded = [];

  public function statuses()
  {
    return $this->hasMany(Status::class)->orderBy('created_at', 'desc');
  }

  public function status()
  {
    return $this->hasOne(Status::class)->orderBy('created_at', 'desc');
  }
}
