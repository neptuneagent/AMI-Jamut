<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'satuan', 'target'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function responseDetails()
    {
        return $this->hasMany(ResponseDetail::class);
    }

    public function findings()
    {
        return $this->hasMany(ResponseFinding::class);
    }

    public function evidences()
    {
        return $this->hasMany(ResponseEvidence::class);
    }

    public function prodiresponses()
    {
        return $this->hasMany(ResponseProdi::class);
    }

    public function delete()
    {
        $this->responseDetails()->each(function ($detail) {
            $detail->delete();
        });

        $this->findings()->each(function ($finding) {
            $finding->delete();
        });

        $this->evidences()->each(function ($evidence) {
            $evidence->delete();
        });
        
        parent::delete();
    }
}
