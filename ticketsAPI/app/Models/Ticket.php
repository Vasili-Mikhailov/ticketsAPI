<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "tickets";

    protected $fillable = ['title', 'text', 'author_name', 'author_tel', 'status'];

    public function files()
    {
        return $this->hasMany('App\Models\File');
    }
}
