<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Review::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('clocking_at')
                    ->label('Registro de ponto')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(true),
                Tables\Columns\TextColumn::make('user.company.name')
                    ->label('Empresa')
                    ->color('danger'),
                IconColumn::make('status')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Inspecionar'),
                ViewAction::make()
                    ->label('Anexo')
                    ->url((fn (Review $record) => $record->attachment)),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->role === 'admin') {
            return parent::getEloquentQuery()
                ->join('users', 'users.id', '=', 'user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->where('companies.id', auth()->user()->company->id)
                ->where('status', '=', Review::STATUS_PENDING)
                ->select('reviews.*');
        }

        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
