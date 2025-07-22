<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status_id', 'user_id'];
    
    public function status()
    {
        return $this->belongsTo(Status::class);
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