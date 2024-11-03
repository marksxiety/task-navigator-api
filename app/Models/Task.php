<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['status_id', 'percentage', 'subject', 'system_id', 'priority_id', 'definition'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function priority()
    {
        return $this->belongsTo(Status::class, 'priority_id');
    }
}
