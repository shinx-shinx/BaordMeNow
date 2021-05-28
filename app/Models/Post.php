<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded;


    // public function scopeFilter($query, $filters)
    // {
    //     $query->where('category_id', "%$filters->category_id%");

    //     $query->where('price', '>=' ,$filters->price_from)
    //             ->where('price' ,'<=', $filters->price_to);
    // }
    public function address()
    {
        return $this->hasOne(PostLocation::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }
}
