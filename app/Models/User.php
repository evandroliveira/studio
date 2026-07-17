<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CLIENTE = 'cliente';

    /**
     * The attributes that are mass assignable.
     *
      * @var list<string>
      */
     protected $fillable = [
        'name',
        'email',
        'password',
        'role',
          'google_id',
          'google_avatar',
          'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwnerEmail(): bool
    {
        $ownerEmail = trim((string) config('auth.owner_email', 'admin@studio.com'));

        return $ownerEmail !== ''
            && strcasecmp((string) $this->email, $ownerEmail) === 0;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->isOwnerEmail();
    }
}
