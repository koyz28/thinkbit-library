<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = [
        'isb',
        'author',
        'title',
        'publication_year',
        'publisher',
        'cover_type',
        'cover',
    ];
}
