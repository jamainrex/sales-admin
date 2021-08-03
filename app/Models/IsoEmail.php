<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsoEmail extends Model
{
    use HasFactory;

    public function iso()
    {
        return $this->belongsTo(Iso::class, 'iso_id');
    }
}
