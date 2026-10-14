<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'Pending', 'confirmed' => 'Confirmed', 'out_for_delivery' => 'Out for delivery',
        'completed' => 'Completed', 'cancelled' => 'Cancelled', 'rejected' => 'Rejected',
    ];
    private const CLASSES = ['pending' => 'warning', 'confirmed' => 'info', 'out_for_delivery' => 'primary', 'completed' => 'success', 'cancelled' => 'secondary', 'rejected' => 'danger'];

    protected $fillable = ['user_id', 'pharmacy_id', 'drug_id', 'quantity', 'unit_price', 'total', 'fulfilment', 'delivery_address', 'phone', 'note', 'status'];
    protected $casts = ['unit_price' => 'decimal:2', 'total' => 'decimal:2'];

    public function getStatusLabelAttribute(): string { return self::STATUSES[$this->status] ?? $this->status; }
    public function getStatusClassAttribute(): string { return self::CLASSES[$this->status] ?? 'secondary'; }

    public function user() { return $this->belongsTo(User::class); }
    public function pharmacy() { return $this->belongsTo(Pharmacy::class); }
    public function drug() { return $this->belongsTo(Drug::class); }

    /** Statuses the pharmacy may move this order to next. */
    public function nextStatuses(): array
    {
        return match ($this->status) {
            'pending' => ['confirmed', 'rejected'],
            'confirmed' => $this->fulfilment === 'delivery' ? ['out_for_delivery', 'completed'] : ['completed'],
            'out_for_delivery' => ['completed'],
            default => [],
        };
    }
}
