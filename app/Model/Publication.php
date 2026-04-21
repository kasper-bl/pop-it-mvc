<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Request;

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
        'image_path'
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
    
    public static function createFromRequest(Request $request, ?string $imagePath = null): self
    {
        return self::create([
            'title' => $request->title,
            'publication_date' => $request->publication_date,
            'staff_id' => $request->staff_id,
            'edition_id' => $request->edition_id,
            'index_type_id' => $request->index_type_id,
            'image_path' => $imagePath
        ]);
    }
    
    public function updateFromRequest(Request $request, ?string $imagePath = null): bool
    {
        $this->title = $request->title;
        $this->publication_date = $request->publication_date;
        $this->staff_id = $request->staff_id;
        $this->edition_id = $request->edition_id;
        $this->index_type_id = $request->index_type_id;
        
        if ($imagePath !== null) {
            $this->image_path = $imagePath;
        }
        
        return $this->save();
    }
    
    public function canEdit($user): bool
    {
        return ($user->role_id == 1 || $this->staff_id == $user->supervisor_id);
    }
}