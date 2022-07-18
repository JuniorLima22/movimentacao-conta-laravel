<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;

    protected $fillable = ['pessoa_id', 'numero'];

    public function pessoa()
    {
        return $this->belongsTo('App\Models\Pessoa');
    }
}
