<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stations_petrol_quantity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'quantities', 'tmam_type', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
