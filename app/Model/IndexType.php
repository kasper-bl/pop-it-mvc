<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class IndexType extends Model
{
    public $timestamps = false;
    protected $table = 'index_type';
    protected $primaryKey = 'index_type_id';

    protected $fillable = ['name'];
}