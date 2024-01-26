<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    // Define the relationship with the Form model
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    
    public function standards()
    {
        return $this->hasMany(Standard::class);
    }
    
    public function delete()
    {
        $this->standards()->each(function ($standard) {
            $standard->delete();
        });

        parent::delete();
    }
}
