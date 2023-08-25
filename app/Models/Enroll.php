<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'enrolls';
    
    protected $keyType = 'string';

    public function edicion()
    {
        return $this->belongsTo(Edicion::class, 'edition_id','id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'course_id','id');
    }

}
