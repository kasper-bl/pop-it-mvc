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
        'index_type_id',
        'image_path'  // ← добавить
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'supervisor_id');
    }

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id', 'edition_id');
    }

    public function indexType()
    {
        return $this->belongsTo(IndexType::class, 'index_type_id', 'index_type_id');
    }
}