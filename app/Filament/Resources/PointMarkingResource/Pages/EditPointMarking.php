<?php

namespace App\Filament\Resources\PointMarkingResource\Pages;

use App\Filament\Resources\PointMarkingResource;
use App\Models\PointMarking;
use App\Models\Review;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditPointMarking extends EditRecord
{
    protected static string $resource = PointMarkingResource::class;
    private Review $review;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int | string $key): Model
    {
        $record = PointMarking::query()->find($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel($this->getModel(), [$key]);
        }

        return $record;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (auth()->user()->isAdmin()) {
            unset($data['description']);
            unset($data['attachments']);

            $record->update($data);

            return $record;
        }
        $data['status'] = $this->defineStatus($record);
        $dataReview = $this->buildReviewData($data, $record);

        $this->HandleUpdateData($data, $record);

        $record->review()->create($dataReview);

        return $record;
    }

    private function defineStatus(Model $record): string
    {
        return auth()->user()->isAdmin()? $record::STATUS_APPROVED : $record::STATUS_PENDING;
    }

    private function HandleUpdateData(array $data, Model $record): void
    {
        if (auth()->user()->isAdmin()) {
            unset($data['description']);
            unset($data['attachments']);

            $record->update($data);

            return;
        }

        $status = [];
        $status['status'] = $data['status'];
        $record->update($status);
    }

    private function buildReviewData(array $data, Model $record): array
    {
        $dataReview['attachments'] = $data['attachments'];
        $dataReview['description'] = $data['description'];
        $dataReview['clocking_at'] = $data['clocking_at'];
        $dataReview['user_id'] = $record->user_id;
        $dataReview['status'] = Review::STATUS_PENDING;

        return $dataReview;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
