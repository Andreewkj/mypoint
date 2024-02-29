<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Uid\Ulid;

class Company extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'cnpj'
    ];

    protected static function booted()
    {
        static::creating(fn(Model $model) => $model->id = (string) Ulid::generate());
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
