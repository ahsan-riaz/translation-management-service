<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Translation;
use Illuminate\Http\JsonResponse;

class TranslationExportController
{
    public function export(string $locale): JsonResponse
    {
        $translations = $this->service->export($locale);
        return response()->json($translations);
    }
}
