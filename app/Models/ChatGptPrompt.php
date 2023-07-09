<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGptPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt',
    ];

    public function responses()
    {
        return $this->hasMany(ChatGptResponse::class);
    }
}
