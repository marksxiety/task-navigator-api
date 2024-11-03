<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    protected $fillable = ['name'];
    protected $table = 'modes';
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
