<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_owner')
            ->using(AccountOwner::class)
            ->withTimestamps();
    }
}
