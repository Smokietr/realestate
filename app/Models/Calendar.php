<?php

namespace App\Models;

use App\Http\Scopes\CalendarScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CalendarScope());
    }

    protected $with = [
        'Customer', 'Consultant'
    ];

    public function Customer() {
        return $this->hasOne(Customers::class, 'id', 'customer');
    }

    public function Consultant() {
        return $this->hasOne(User::class, 'id', 'consultant');
    }
}
