<?php

namespace App\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Uid\Ulid;

class Review extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    public $incrementing = false;

    protected $fillable = [
        'description',
        'clocking_at',
        'user_id',
        'point_marking_id',
        'approved',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    protected static function booted()
    {
        static::creating(fn(Model $model) => $model->id = (string) Ulid::generate());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function point_marking()
    {
        return $this->belongsTo(PointMarking::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Solicitação  de Alteração')
                ->columns(2)
                ->collapsible(true)
                ->schema([
                    DateTimePicker::make('clocking_at')
                        ->label('Marcar o ponto')
                        ->displayFormat('d/m/Y H:i')
                        ->markAsRequired(false)
                        ->columnSpan(1)
                        ->readOnly()
                        ->required(),
                    DateTimePicker::make('original_clocking_at')
                        ->label('Marcação original')
                        ->displayFormat('d/m/Y H:i')
                        ->markAsRequired(false)
                        ->columnSpan(1)
                        ->readOnly(),
                    TextArea::make('description')
                        ->label('Descrição')
                        ->columnSpanFull()
                        ->readOnly(),
                    ToggleButtons::make('status')
                        ->label('Aprovado?')
                        ->options([
                            'approved' => 'Aprovar',
                            'rejected' => 'Reprovar',
                        ])
                        ->colors([
                            'rejected' => 'danger',
                            'approved' => 'success',
                        ])->inline(),
                ]),
        ];
    }
}
