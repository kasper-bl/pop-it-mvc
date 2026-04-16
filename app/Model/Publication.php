<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    public $timestamps = false;
    protected $table = 'publications';
    protected $primaryKey = 'publication_id';

    protected $fillable = [
        'title',
        'publication_date',
        'staff_id',
        'edition_id',
        'index_type_id'
    ];

    // Связь с сотрудником (научным руководителем)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'supervisor_id');
    }

    // Связь с типом издания
    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id', 'edition_id');
    }

    // Связь с типом индекса
    public function indexType()
    {
        return $this->belongsTo(IndexType::class, 'index_type_id', 'index_type_id');
    }
}