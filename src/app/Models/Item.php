<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'description',
        'condition',
        'image_path',
        'brand',
        'status',
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
        return $this->hasMany(Order::class);
    }

    /*public function isSold(): bool //:bool足した
    {
        return $this->orders()->exists();
    }一旦残しておく問題なければ削除*/

    public function isSold(): bool //この部分を追加した
    {
        return $this->status === 'sold_out';
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if(!empty($keyword)){
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    }

    public function scopeBoughtBy($query, $userId)
    {
        return $query->whereIn('id', Order::where('buyer_id', $userId)
            ->where('status', 'completed')
            ->pluck('item_id'));
    }

    public function scopeInTransaction($query, $userId)
    {
        return $query->whereHas('orders', function($q) use ($userId) {
            $q->where('status', '!=', 'completed')
                ->where(function($q2) use ($userId) {
                    $q2->where('buyer_id', $userId)
                        ->orWhereHas('item', function($q3) use ($userId) {
                            $q3->where('seller_id', $userId);
                        });
                });
        });
    }
}