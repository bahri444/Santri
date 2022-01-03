<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;
    protected $table = 'santri';
    protected $fillable = [
        'nama',
        'jk',
        'alamat',
        'tempat',
        'tgl_lahir',
    ];
}
