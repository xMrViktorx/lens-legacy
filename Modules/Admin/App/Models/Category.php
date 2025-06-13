<?php

namespace Modules\Admin\App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Admin\Database\factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public $translatable = ['name', 'description'];
    
    protected static function newFactory(): CategoryFactory
    {
        //return CategoryFactory::new();
    }

    public function albums()
    {
        return $this->hasMany('Modules\Admin\App\Models\Album');
    }
}
