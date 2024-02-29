<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
