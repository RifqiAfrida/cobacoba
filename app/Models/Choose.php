<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choose extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pollings_id'
    ];

    // public  function userchoose()
    // {
    //     return $this->hasMany(UserChoose::class);
    // }
    // public function polling()
    // {
    //     return $this->belongsTo(Polling::class, 'pollings_id');
    // }
       public function user()
    {
        return $this->belongsToMany(User::class, "user_chooses", "id", "id");
    }
}
