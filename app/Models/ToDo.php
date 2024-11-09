<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        'description',
        'status',
    ];

    //Accessors
    public function getTaskNameAttribute($val)
    {
        return ucfirst($val);
    }
    public function getDescriptionAttribute($val)
    {
        return ucfirst($val);
    }
     public function getStatusAttribute($val)
    {
        return ucfirst($val);
    }

    //Mutators
    public function setTaskNameAttribute($val)
    {
        $this->attributes['task_name']=ucfirst($val);
    }
    public function setDescriptionAttribute($val)
    {
        $this->attributes['description']=ucfirst($val);
    }
    public function setStatusAttribute($val)
    {
        $this->attributes['status']=ucfirst($val);
    }

}
