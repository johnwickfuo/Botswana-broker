<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone_number', 'dob',
        'social_media', 'address', 'city', 'state', 'country', 'document_type',
        'id_number', 'frontimg', 'backimg', 'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
