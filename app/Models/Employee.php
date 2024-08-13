<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'phone', 'division_id', 'position'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
