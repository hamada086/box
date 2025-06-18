<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'service_type', // ['design', 'development', 'marketing', 'seo']
        'status', // ['pending', 'in_progress', 'waiting_client', 'completed']
        'start_date',
        'deadline',
        'budget',
        'team_notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'budget' => 'decimal:2'
    ];

    // العلاقة مع العميل
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // العلاقة مع أعضاء الفريق
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'project_team')
                   ->withPivot('role', 'hours_worked')
                   ->withTimestamps();
    }

    // وصولات (Accessors)
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'معلق',
            'in_progress' => 'قيد التنفيذ',
            'waiting_client' => 'بانتظار العميل',
            'completed' => 'مكتمل',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'primary',
            'waiting_client' => 'info',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    // نطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeLate($query)
    {
        return $query->where('deadline', '<', now())
                    ->where('status', '!=', 'completed');
    }
}