<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['user_id', 'name', 'address', 'area', 'phone', 'email', 'description', 'opening_hours', 'status'];

    public function scopeVerified($q) { return $q->where('status', 'verified'); }
    public function isVerified(): bool { return $this->status === 'verified'; }

    public function owner() { return $this->belongsTo(User::class, 'user_id'); }
    public function listings() { return $this->hasMany(PharmacyDrug::class); }
    public function orders() { return $this->hasMany(Order::class); }
}
