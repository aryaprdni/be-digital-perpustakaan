<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    protected $table = 'users';
    protected $primaryKey= 'id';
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'role',
        'password'
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    public function books(): HasMany {
        return $this->hasMany(Book::class, "created_by", "id");
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }

    public function setRoleAttribute($value)
    {
        $this->attributes['role'] = $value ?: 'user';
    }
}
