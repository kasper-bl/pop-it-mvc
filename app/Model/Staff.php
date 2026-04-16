<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class Staff extends Model implements IdentityInterface
{
    public $timestamps = false;
    protected $table = 'staff';
    protected $primaryKey = 'supervisor_id';  // ← исправлено: было 'id_staff'

    protected $fillable = [
        'login',
        'password',
        'name',
        'surname',
        'patronymic',
        'department',
        'role_id'
    ];

    protected static function booted()
    {
        static::creating(function ($staff) {
            $staff->password = md5($staff->password);
        });
    }

    // Выборка пользователя по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('supervisor_id', $id)->first();  // ← исправлено
    }

    // Возврат первичного ключа
    public function getId(): int
    {
        return $this->supervisor_id;  // ← исправлено
    }

    // Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        return self::where([
            'login' => $credentials['login'],
            'password' => md5($credentials['password'])
        ])->first();
    }
    
    // Связь с ролями
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'role_id');
    }
}