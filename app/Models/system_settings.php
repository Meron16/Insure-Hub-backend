<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class system_settings extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_id';
    
    protected $fillable = [
        'key',
        'value',
        'description',
        'updated_by'
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}