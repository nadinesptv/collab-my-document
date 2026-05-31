<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // Pastikan properti ini bernilai true (bawaan Laravel sebenarnya sudah true)
    public $timestamps = true;

    protected $fillable = ['title', 'content', 'user_id'];
    
    public function revisions()
    {
        return $this->hasMany(Revision::class)->latest();
    }
}