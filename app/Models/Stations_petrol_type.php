<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stations_petrol_type extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'petrol_type', 'storage_num', 'storage_capacity'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
