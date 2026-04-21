<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Request;

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

    public function getFullName(): string
    {
        return trim($this->surname . ' ' . $this->name . ' ' . $this->patronymic);
    }

    public function supervisor()
    {
        return $this->belongsTo(Staff::class, 'supervisor_id', 'supervisor_id');
    }

    public function dissertation()
    {
        return $this->hasOne(Dissertation::class, 'postgraduate_id', 'postgraduate_id');
    }

    
    public static function createFromRequest(Request $request, $user): self
    {
        $supervisorId = $user->role_id == 1
            ? ($request->supervisor_id ?? $user->supervisor_id)
            : $user->supervisor_id;
        
        return self::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'supervisor_id' => $supervisorId
        ]);
    }
    
    public function updateFromRequest(Request $request, $user): bool
    {
        $this->name = $request->name;
        $this->surname = $request->surname;
        $this->patronymic = $request->patronymic;
        
        if ($user->role_id == 1 && $request->supervisor_id) {
            $this->supervisor_id = $request->supervisor_id;
        }
        
        return $this->save();
    }
    
    public function canEdit($user): bool
    {
        return ($user->role_id == 1 || $this->supervisor_id == $user->supervisor_id);
    }
}