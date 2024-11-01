<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être remplis lors de la création ou la mise à jour d'une tâche
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
    ];

    // Les attributs à caster dans des types spécifiques
    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relation : Une tâche appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
