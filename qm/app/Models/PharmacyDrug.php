<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyDrug extends Model
{
    protected $table = 'pharmacy_drugs';
    protected $fillable = ['pharmacy_id', 'drug_id', 'price', 'quantity', 'is_available'];
    protected $casts = ['price' => 'decimal:2', 'is_available' => 'boolean'];

    public function pharmacy() { return $this->belongsTo(Pharmacy::class); }
    public function drug() { return $this->belongsTo(Drug::class); }

    public function canOrder(): bool { return $this->is_available && $this->quantity > 0; }

    public function getAvailabilityLabelAttribute(): string
    {
        if (! $this->canOrder()) return 'Unavailable';
        return $this->quantity <= 5 ? 'Low stock' : 'In stock';
    }

    public function getAvailabilityClassAttribute(): string
    {
        return match ($this->availability_label) { 'In stock' => 'success', 'Low stock' => 'warning', default => 'secondary' };
    }
}
