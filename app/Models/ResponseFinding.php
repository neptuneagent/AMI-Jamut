<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseFinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'description',
        'criteria_id',
        'root_cause',
        'recommendation',
        'category',
    ];

    /**
     * Get the response that owns the finding.
     */
    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    /**
     * Get the criteria associated with the finding.
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
