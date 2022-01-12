<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganSantri extends Model
{
    use HasFactory;
    protected $table = 'ruangan_santri';
    protected $fillable = ['santri_id', 'ruangan_id'];
}
