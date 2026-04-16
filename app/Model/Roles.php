<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $timestamps = false;
    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = ['name'];

    // Связь с сотрудниками
    public function staff()
    {
        return $this->hasMany(Staff::class, 'role_id', 'role_id');
    }
}