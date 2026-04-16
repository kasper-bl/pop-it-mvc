<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Dissertation extends Model
{
    public $timestamps = false;
    protected $table = 'dissertations';
    protected $primaryKey = 'dissertation_id';

    protected $fillable = [
        'postgraduate_id',
        'topic',
        'approval_date',
        'status_id',
        'vak_specialty'
    ];

    // Связь с аспирантом
    public function postgraduate()
    {
        return $this->belongsTo(Postgraduate::class, 'postgraduate_id', 'postgraduate_id');
    }

    // Связь со статусом диссертации
    public function status()
    {
        return $this->belongsTo(DissertationStatus::class, 'status_id', 'status_id');
    }
}