<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;
    
    protected $fillable = ['description', 'weight'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
