<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\transaction;

class transaction extends Model
{
    protected $fillable = ['user_id', 'type', 'amount', 'related_user_id'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
