<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\AWS\SignWithCloudFront;

class Curso extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'coursers';

    protected $keyType = 'string';

    public function ediciones()
    {
        return $this->hasMany(Edicion::class, 'course_id','id')->orderBy('init_date', 'desc');
    }

    public static function getPossibleEnumValues ($column) {
        $instance = new static;
        $arr =  \DB::select(\DB::raw('SHOW COLUMNS FROM '.$instance->getTable().' WHERE Field = "'.$column.'"'));
        if (count($arr) == 0){
            return array();
        }
        $enumStr = $arr[0]->Type;
        preg_match_all("/'([^']+)'/", $enumStr, $matches);
        return isset($matches[1]) ? $matches[1] : [];
    }

    public function getImagenAttribute() {
        if ($this->image) {
            $result = SignWithCloudFront::sign($this->image, 'course', 5);
            if ($result->Success == true) {
                return $result->Link;
             } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function cursosEdicionesVisibles()
    {
        return $this->hasMany(Edicion::class, 'course_id','id')->where('visible',true)->orderBy('init_date', 'desc');
    }

    public function cursosEdicionesAbiertas()
    {
        return $this->hasMany(Edicion::class, 'course_id','id')->where('is_open',true)->orderBy('init_date', 'desc');
    }

    public function cursosEdiciones()
    {
        return $this->hasMany(Edicion::class, 'course_id','id')->orderBy('init_date', 'desc');
    }

    
    /* public static function boot()
    {
         parent::boot();
         self::creating(function($model){
             $model->id = self::generateUuid();
         });
    }
    
    public static function generateUuid()
    {
         return Uuid::generate()->string;
    } */
   
    
}
