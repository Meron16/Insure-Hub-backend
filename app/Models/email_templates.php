<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class email_templates extends Model
{
    use HasFactory;

    protected $primaryKey = 'template_id';
    
    protected $fillable = [
        'name',
        'subject',
        'body',
        'variables'
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public function sentEmails()
    {
        return $this->hasMany(sent_emails::class, 'template_id');
    }
}