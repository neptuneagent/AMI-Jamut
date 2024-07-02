<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function delete()
    {
        $this->criterias()->each(function ($criteria) {
            $criteria->delete();
        });

        parent::delete();
    }
}
