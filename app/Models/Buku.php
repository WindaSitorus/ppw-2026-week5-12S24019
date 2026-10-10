<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = ['isbn', 'judul', 'penulis', 'tahun_terbit', 'stok', 'kategori_id'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}