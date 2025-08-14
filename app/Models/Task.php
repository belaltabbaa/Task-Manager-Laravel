<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id','title','status','user_id','due_date','attachment'
    ];
    protected $table = 'tasks';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
