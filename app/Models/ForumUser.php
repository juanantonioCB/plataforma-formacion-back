<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumUser extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'forum_users';
    
    protected $keyType = 'string';

    

}
