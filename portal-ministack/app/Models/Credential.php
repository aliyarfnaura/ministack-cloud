<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ministack_account_id',
        'access_key_id',
        'secret_access_key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}