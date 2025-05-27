<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSubscription extends Model
{
    protected $fillable = ['name', 'email', 'url', 'active', 'user_id'];

}
