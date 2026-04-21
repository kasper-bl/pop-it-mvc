<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Request;

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

    public function postgraduate()
    {
        return $this->belongsTo(Postgraduate::class, 'postgraduate_id', 'postgraduate_id');
    }

    public function status()
    {
        return $this->belongsTo(DissertationStatus::class, 'status_id', 'status_id');
    }
    
    
    public static function createFromRequest(Request $request): self
    {
        return self::create([
            'postgraduate_id' => $request->postgraduate_id,
            'topic' => $request->topic,
            'approval_date' => $request->approval_date,
            'status_id' => $request->status_id,
            'vak_specialty' => $request->vak_specialty
        ]);
    }
    
    public function updateFromRequest(Request $request): bool
    {
        $this->topic = $request->topic;
        $this->approval_date = $request->approval_date;
        $this->status_id = $request->status_id;
        $this->vak_specialty = $request->vak_specialty;
        
        return $this->save();
    }
    
    public function canEdit($user): bool
    {
        return ($user->role_id == 1 || $this->postgraduate->supervisor_id == $user->supervisor_id);
    }
}