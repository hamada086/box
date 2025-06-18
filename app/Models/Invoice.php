<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'project_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'status', // ['draft', 'sent', 'paid', 'overdue']
        'items',
        'subtotal',
        'tax',
        'total_amount',
        'notes',
        'payment_method'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    // العلاقة مع العميل
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // العلاقة مع المشروع
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // وصولات (Accessors)
    public function isOverdue(): bool
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'draft' => 'مسودة',
                'sent' => 'مرسلة',
                'paid' => 'مدفوعة',
                'overdue' => 'متأخرة',
                default => 'غير معروفة'
            }
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'draft' => 'secondary',
                'sent' => 'info',
                'paid' => 'success',
                'overdue' => 'danger',
                default => 'warning'
            }
        );
    }

    // نطاقات (Scopes)
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid');
    }
}