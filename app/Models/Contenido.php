<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Library\AWS\SignWithCloudFront;

class Contenido extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'contents';
    public $timestamps = false;

    public function getImagenAttribute() {
        if ($this->picture) {
            $result = SignWithCloudFront::sign($this->picture, 'content', 5);
            if ($result->Success == true) {
                return $result->Link;
             } else {
                return '';
            }
        } else {
            return '';
        }
    }

}
