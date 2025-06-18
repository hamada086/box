<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class TeamMember extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'position',
        'department', // ['design', 'development', 'marketing', 'management']
        'photo_path',
        'is_active',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime'
    ];

    // العلاقة مع المشاريع
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team')
                   ->withPivot('role', 'hours_worked')
                   ->withTimestamps();
    }

    // وصولات (Accessors)
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path ? asset('storage/'.$this->photo_path) : asset('images/default-avatar.png');
    }

    // نطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }
}