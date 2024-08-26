<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseProdi extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_finding_id',
        'comment',
        'corrective_action_plan',
        'corrective_action_schedule',
        'preventive_action_plan',
        'preventive_action_schedule',
        'corrective_action_responsible',
        'preventive_action_responsible',
    ];

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
