<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    public $timestamps = false;
    protected $table = 'edition';
    protected $primaryKey = 'edition_id';

    protected $fillable = ['name'];
}