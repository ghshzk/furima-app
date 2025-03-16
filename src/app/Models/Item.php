<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'condition',
        'image_path',
        'brand',
        'user_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'categorizations');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class,'likes')->withTimestamps();
    }

    public function likedBy($user)
    {
        if(!$user) {
            return false;
        }
        return $this->likedByUsers->contains($user->id);
    }

    public function likeCount()
    {
        return $this->likedByUsers()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentCount()
    {
        return $this->comments()->count();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id');
    }

    public function isSold()
    {
        return $this->orders()->exists();
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if(!empty($keyword)){
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    }
}