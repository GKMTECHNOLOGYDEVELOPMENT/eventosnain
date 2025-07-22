<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name', 'color', 'order', 'user_id'];
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope para filtrar por usuario
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}