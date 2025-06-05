<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(protected TranslationService $service) {}

    public function index(Request $request): JsonResponse
    {
        $translations = $this->service->list($request->only(['key', 'content', 'tag']));
        return response()->json($translations);
    }

    public function store(StoreTranslationRequest $request): JsonResponse
    {
        $translation = $this->service->create($request->validated());
        return response()->json($translation, 201);
    }

    public function show($id): JsonResponse
    {
        $translation = Translation::with(['values', 'tags'])->findOrFail($id);
        return response()->json($translation);
    }

    public function update(UpdateTranslationRequest $request, $id): JsonResponse
    {
        $translation = Translation::findOrFail($id);
        $updated = $this->service->update($translation, $request->validated());
        return response()->json($updated);
    }
}
