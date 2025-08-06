<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sent_emails extends Model
{
    use HasFactory;

    protected $primaryKey = 'email_id';
    
    protected $fillable = [
        'template_id',
        'recipient_email',
        'subject',
        'body',
        'status',
        'error_message'
    ];

    public function template()
    {
        return $this->belongsTo(email_templates::class, 'template_id');
    }
}