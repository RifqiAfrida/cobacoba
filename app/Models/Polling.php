<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
    use HasFactory;

        protected $fillable = [
        'pollingname',
        'start_date',
        'end_date',
        'status',
    ];

    public function choose()
    {
        return $this->hasMany(Choose::class, "pollings_id", "id");
    }
}
