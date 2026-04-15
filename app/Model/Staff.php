<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class Staff extends Model implements IdentityInterface
{
    public $timestamps = false;
    protected $table = 'staff';
    protected $primaryKey = 'id_staff';

    protected $fillable = [
        'login', 'password', 'name', 'surname', 'patronymic', 'department'
    ];

    protected static function booted()
    {
        static::creating(function ($staff) {
            $staff->password = md5($staff->password);
        });
    }

    public function findIdentity(int $id)
    {
        return self::where('id_staff', $id)->first();
    }

    public function getId(): int
    {
        return $this->id_staff;
    }

    public function attemptIdentity(array $credentials)
    {
        return self::where([
            'login' => $credentials['login'],
            'password' => md5($credentials['password'])
        ])->first();
    }
}