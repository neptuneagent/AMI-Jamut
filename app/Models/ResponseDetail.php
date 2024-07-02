<?php

// app/Models/ResponseDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'criteria_id',
        'answer',
        'information'
    ];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
