<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointMarkingResource\Pages;
use App\Filament\Resources\PointMarkingResource\RelationManagers;
use App\Models\PointMarking;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointMarkingResource extends Resource
{
    protected static ?string $model = PointMarking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(PointMarking::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('clocking_at')
                    ->label('Registro de ponto')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Local da marcação'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(true),
                Tables\Columns\TextColumn::make('user.company.name')
                    ->label('Empresa')
                    ->color('danger'),
                IconColumn::make('review.status')
                    ->icon(fn (string $state): string => match ($state) {
                        'approved' => 'heroicon-m-check-circle',
                        'rejected' => 'heroicon-c-x-circle',
                        'pending' => 'heroicon-s-clock',
                    })->color(fn (string $state): string => match ($state) {
                        'rejected' => 'danger',
                        'approved' => 'success',
                        'pending' => 'warning',
                    }),
            ])
            ->filters([
                Filter::make('pessoais')
                    ->query(fn (Builder $query): Builder => $query->where('user_id', auth()->id())),
                Filter::make('clocking_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('clocking_at', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPointMarkings::route('/'),
            'create' => Pages\CreatePointMarking::route('/create'),
            'edit' => Pages\EditPointMarking::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->role === 'admin') {
            return parent::getEloquentQuery()
                ->join('users', 'users.id', '=', 'point_markings.user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->where('companies.id', auth()->user()->company->id)
                ->select('point_markings.*');
        }

        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
