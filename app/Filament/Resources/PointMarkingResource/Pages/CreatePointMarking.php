<?php

namespace App\Filament\Resources\PointMarkingResource\Pages;

use App\Filament\Resources\PointMarkingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePointMarking extends CreateRecord
{
    protected static string $resource = PointMarkingResource::class;

    public function mutateFormDataBeforeCreate($data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
