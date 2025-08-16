<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'content',
        'image_path',
        'read_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeUnreadFor($query, $userId)
    {
        return $query->whereNull('read_at')->where('sender_id', '!=', $userId);
    }
}
