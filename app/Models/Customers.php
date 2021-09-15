<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Calendar() {
        return $this->hasMany(Calendar::class, 'customer', 'id');
    }
}
