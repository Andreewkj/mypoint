<?php

namespace App\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Uid\Ulid;

class PointMarking extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'clocking_at',
        'modified',
    ];
    protected static function booted()
    {
        static::creating(fn(Model $model) => $model->id = (string) Ulid::generate());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Registro de ponto')
                ->columns(2)
                ->collapsible(true)
                ->schema([
                    DateTimePicker::make('clocking_at')
                        ->label('Marcar o ponto')
                        ->displayFormat('d/m/Y H:i')
                        ->default(fn (string $operation): string => $operation === 'create'? 'now' : null)
                        ->readOnly(fn (string $operation): bool => $operation === 'create')
                        ->markAsRequired(false)
                        ->columnSpan(1)
                        ->weekStartsOnMonday()
                        ->required(),
                    TextInput::make('location')
                        ->columnSpanFull()
                        ->label('Local')
                        ->default('R. Alagoas, 1160 - Funcionários')
                        ->required()
                        ->markAsRequired(false)
                        ->maxLength(144)
                        ->columnSpan(1),
                    Toggle::make('modified')
                        ->label('Modificado')
                        ->columnSpan(1)
                        ->hidden(),
                    Textarea::make('description')
                        ->label('Descricão')
                        ->disabled(fn (string $operation): bool => $operation === 'create')
                        ->required(fn (string $operation): bool => $operation === 'edit')
                        ->hidden(fn (string $operation): bool => $operation === 'create'),
                    FileUpload::make('attachments')
                        ->directory('form-attachments')
                        ->visibility('public')
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->disabled(fn (string $operation): bool => $operation === 'create'),
                ]),
        ];
    }
}
