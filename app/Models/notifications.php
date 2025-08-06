<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';
    
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'related_entity_type',
        'related_entity_id',
        'action_url'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}