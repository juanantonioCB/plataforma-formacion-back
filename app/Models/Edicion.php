<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Edicion extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [];
    protected $table = 'editions';

    protected $keyType = 'string';

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

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id','teacher_id')->withDefault();
    }

    public function alumnos()
    {
        return $this->hasMany(Enroll::class, 'edition_id','id');
    }
}
