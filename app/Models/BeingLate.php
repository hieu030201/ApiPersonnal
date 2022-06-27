<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeingLate extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $fillable = ['name'];

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }


}
