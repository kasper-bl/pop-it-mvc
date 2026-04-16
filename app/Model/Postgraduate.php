<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Postgraduate extends Model
{
    public $timestamps = false;
    protected $table = 'postgraduates';
    protected $primaryKey = 'postgraduate_id';

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'supervisor_id'
    ];

    // Полное имя аспиранта
    public function getFullName(): string
    {
        return trim($this->surname . ' ' . $this->name . ' ' . $this->patronymic);
    }

    // Связь с научным руководителем (сотрудником)
    public function supervisor()
    {
        return $this->belongsTo(Staff::class, 'supervisor_id', 'supervisor_id');
    }

    // Связь с диссертацией (один к одному)
    public function dissertation()
    {
        return $this->hasOne(Dissertation::class, 'postgraduate_id', 'postgraduate_id');
    }
}