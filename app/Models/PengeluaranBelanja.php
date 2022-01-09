<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranBelanja extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran_belanja';
    protected $fillable = ['id', 'nm_barang', 'jml', 'satuan', 'harga_item'];
}
