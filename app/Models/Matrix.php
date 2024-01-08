<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Matrix extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['user_id', 'title', 'slug', 'rows', 'columns', 'is_public'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}