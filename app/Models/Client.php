<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'status', // ['new', 'interested', 'active', 'inactive']
        'source', // كيف عرف عنا العميل
        'notes',
        'last_contacted_at'
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    // العلاقة مع المشاريع
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    // العلاقة مع الفواتير
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // العلاقة مع الرسائل
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // وصولات (Accessors)
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'new' => 'جديد',
            'interested' => 'مهتم',
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'new' => 'info',
            'interested' => 'warning',
            'active' => 'success',
            'inactive' => 'danger',
            default => 'secondary'
        };
    }
}