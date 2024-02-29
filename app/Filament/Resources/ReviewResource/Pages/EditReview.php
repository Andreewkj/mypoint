<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\PointMarking;
use App\Models\Review;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int | string $key): Model
    {
        $record = Review::query()->find($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel($this->getModel(), [$key]);
        }

        return $record;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        unset($data['original_clocking_at']);
        $record->update($data);

        $pointMarking = [];
        $pointMarking['clocking_at'] = $data['clocking_at'];
        $pointMarking['status'] = $data['status'];
        $record->point_marking()->update($pointMarking);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $pointMarking = PointMarking::query()->find($data['point_marking_id']);
        $data['original_clocking_at'] = $pointMarking->clocking_at;

        return parent::mutateFormDataBeforeFill($data);
    }
}
