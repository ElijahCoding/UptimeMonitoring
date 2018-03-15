<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
  protected $guarded = [];

  public function isUp()
  {
    return substr((string) $this->status_code, 0, 1) === '2';
  }

  public function isDown()
  {
    return !$this->isUp();
  }
}
