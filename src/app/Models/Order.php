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

    public function latestMessage()
    {
        return $this->hasOne(OrderMessage::class)->latestOfMany();
    }

    public function scopeInTransaction($query, $userId)
    {
        return $query->with('latestMessage', 'item')
            ->where('status', '!=', 'completed')
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
            });
    }
}
