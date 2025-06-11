<?php

namespace Modules\Admin\App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Admin\Database\factories\AlbumFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Album extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
    ];

    public $translatable = ['name', 'description'];
    
    protected static function newFactory(): AlbumFactory
    {
        //return AlbumFactory::new();
    }
}
