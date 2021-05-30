<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLocation extends Model
{
    protected $fillable = [
        'post_id',
        'address',
        'city',
        'country',
        'postal_code'
    ];

    protected $hidden = [
        'id',
        'post_id',
        'created_at',
        'updated_at',
    ];

    public function scopeFilterAddress($query, $filters)
    {
        $query->where('address', 'LIKE', '%'.$filters->address.'%')
            ->where('city' ,'LIKE' ,"%$filters->city%")
            ->where('state', 'LIKE', "%$filters->state%")
            ->where('country', 'LIKE', "%$filters->country%");
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
