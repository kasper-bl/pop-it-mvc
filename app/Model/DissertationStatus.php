<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class DissertationStatus extends Model
{
    public $timestamps = false;
    protected $table = 'dissertation_statuses';
    protected $primaryKey = 'status_id';

    protected $fillable = ['name'];

    // Связь с диссертациями
    public function dissertations()
    {
        return $this->hasMany(Dissertation::class, 'status_id', 'status_id');
    }
}