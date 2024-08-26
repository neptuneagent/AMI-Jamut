<?php

// app/Models/Response.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'user_id',
        'submitted_at',
        'status',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(ResponseDetail::class);
    }

    public function histories()
    {
        return $this->hasMany(ResponseHistory::class);
    }

    public function evidences()
    {
        return $this->hasMany(ResponseEvidence::class);
    }

    public function findings()
    {
        return $this->hasMany(ResponseFinding::class);
    }


    public function delete()
    {
        $this->details()->each(function ($detail) {
            $detail->delete();
        });

        $this->histories()->each(function ($history) {
            $history->delete();
        });

        $this->evidences()->each(function ($evidence) {
            $evidence->delete();
        });

        $this->findings()->each(function ($finding) {
            $finding->delete();
        });

        parent::delete();
    }
}
