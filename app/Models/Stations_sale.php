<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stations_sale extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sales', 'created_by'];

    protected $casts = [
        'sales' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
