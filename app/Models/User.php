<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone',
        'address',
        'crp',
        'specialties',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'specialties' => 'array',
    ];

    // Relacionamentos
    public function patients()
    {
        return $this->hasMany(Patient::class, 'professional_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'professional_id');
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Financial\Invoice::class, 'professional_id');
    }

    public function sessionNotes()
    {
        return $this->hasMany(\App\Models\SessionNote::class, 'professional_id');
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\Expense::class);
    }

    // Scopes
    public function scopeProfessionals($query)
    {
        return $query->where('type', 'professional');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function isProfessional()
    {
        return $this->type === 'professional';
    }

    public function isAssistant()
    {
        return $this->type === 'assistant';
    }
}