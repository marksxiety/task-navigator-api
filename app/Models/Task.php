<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['subject', 'system_id', 'mode_id', 'definition', 'status_id', 'percentage', 'added_by'];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function system()
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function mode()
    {
        return $this->belongsTo(Mode::class, 'mode_id');
    }
}
