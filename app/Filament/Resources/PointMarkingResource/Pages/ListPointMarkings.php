<?php

namespace App\Filament\Resources\PointMarkingResource\Pages;

use App\Filament\Resources\PointMarkingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointMarkings extends ListRecords
{
    protected static string $resource = PointMarkingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
