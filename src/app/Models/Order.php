<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'item_id',
        'price',
        'payment_method',
        'shipping_address',
        'status',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(OrderMessage::class)->latestOfMany();
    }

    public function scopeInTransaction($query, $userId)
    {
        return $query->with('latestMessage', 'item')
            ->whereIn('status', ['trading', 'pending_complete'])
            ->where(function ($q) use ($userId) {
                $q->where(function ($q2) use ($userId) {
                    $q2->where('buyer_id', $userId)
                        ->where('buyer_rated', false);
                })->orWhere(function ($q2) use ($userId) {
                    $q2->where('seller_id', $userId)
                        ->where('seller_rated', false);
                });
            });
    }

    public function needsReviewBy(User $user): bool
    {
        if ($this->status !== 'pending_complete') {
            return false;
        }

        if ($this->buyer_id === $user->id && !$this->buyer_rated) {
            return true;
        }
        return false;
    }
}
