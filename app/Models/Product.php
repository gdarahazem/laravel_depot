<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts = [
        'createdOn' => 'datetime',
    ];
    public function client() {
        return $this->belongsTo(Client::class, "owner");
    }
}
