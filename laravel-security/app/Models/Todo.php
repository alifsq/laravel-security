<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;
    protected $table = 'todos';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }
}
