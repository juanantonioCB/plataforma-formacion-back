<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\AWS\SignWithCloudFront;

class Administrador extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [];
    protected $table = 'administrators';

    protected $keyType = 'string';

    public function getAvatarAttribute() {
        if ($this->picture) {
            $result = SignWithCloudFront::sign($this->picture, 'person', 5);
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
