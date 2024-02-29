<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\Uid\Ulid;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYEE = 'employee';
    const ROLE_MASTER = 'master';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(fn(Model $model) => $model->id = (string) Ulid::generate());
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->allowedEmail() && $this->hasVerifiedEmail();
    }

    private function allowedEmail(): bool
    {
        $emails = [
            '@onhappy.com',
            '@smart.com',
        ];

        $this->email = strtolower($this->email);

        foreach ($emails as $email) {
            if (str_ends_with($this->email, $email)) {
                return true;
            }
        }

        return false;
    }

    public function point_markings(): HasMany
    {
        return $this->hasMany(PointMarking::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    public function isMaster(): bool
    {
        return $this->role === self::ROLE_MASTER;
    }
}
