<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }
}
