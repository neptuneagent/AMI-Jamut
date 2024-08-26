<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseEvidence extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'file_path', 'response_id', 'criteria_id'];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}

/**
*class ResponseEvidence extends Model
*{
*    use HasFactory;

*    protected $fillable = ['name', 'description', 'file_path', 'response_id'];

*   public function response()
*    {
*        return $this->belongsTo(Response::class);
*    }
*}
*/
