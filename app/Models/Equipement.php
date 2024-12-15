<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'serial_number',
        'purchase_date',
        'status',
    ];
    public function employes()
    {
        return $this->belongsToMany(Employe::class, 'employee_equipement', 'equipement_id', 'employe_id');

    }
}
