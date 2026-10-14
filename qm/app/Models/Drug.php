<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $fillable = ['name', 'generic_name', 'category', 'description'];
    public function listings() { return $this->hasMany(PharmacyDrug::class); }
}
