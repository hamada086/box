<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'team_member_id',
        'channel', // ['whatsapp', 'email', 'sms', 'system']
        'direction', // ['inbound', 'outbound']
        'message',
        'attachments', // JSON array of file paths
        'read_at',
        'status' // ['sent', 'delivered', 'read', 'failed']
    ];

    protected $casts = [
        'attachments' => 'array',
        'read_at' => 'datetime'
    ];

    // العلاقة مع العميل
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // العلاقة مع عضو الفريق
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }

    // وصولات (Accessors)
    public function getDirectionTextAttribute(): string
    {
        return $this->direction === 'inbound' ? 'وارد' : 'صادر';
    }

    public function getChannelIconAttribute(): string
    {
        return match($this->channel) {
            'whatsapp' => 'fab fa-whatsapp',
            'email' => 'fas fa-envelope',
            'sms' => 'fas fa-sms',
            default => 'fas fa-comment-alt'
        };
    }

    // نطاقات (Scopes)
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeWhatsapp($query)
    {
        return $query->where('channel', 'whatsapp');
    }
}