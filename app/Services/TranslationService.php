<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Translation;
use App\Models\TranslationValue;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TranslationService
{
    public function list(array $filters): Collection
    {
        $query = Translation::with(['values', 'tags']);

        if (!empty($filters['key'])) {
            $query->where('key', 'like', "%{$filters['key']}%");
        }

        if (!empty($filters['content'])) {
            $query->whereHas('values', function ($q) use ($filters) {
                $q->where('value', 'like', "%{$filters['content']}%");
            });
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('name', $filters['tag']);
            });
        }

        return $query->get();
    }

    public function create(array $data): Translation
    {
        $translation = Translation::create([
            'key' => $data['key'],
            'group' => $data['group'] ?? null,
        ]);

        foreach ($data['values'] as $val) {
            $translation->values()->create($val);
        }

        if (!empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(fn($tag) =>
            Tag::firstOrCreate(['name' => $tag])->id
            );
            $translation->tags()->sync($tagIds);
        }

        return $translation->load(['values', 'tags']);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update([
            'key' => $data['key'] ?? $translation->key,
            'group' => $data['group'] ?? $translation->group,
        ]);

        if (!empty($data['values'])) {
            foreach ($data['values'] as $val) {
                TranslationValue::updateOrCreate(
                    ['translation_id' => $translation->id, 'locale' => $val['locale']],
                    ['value' => $val['value']]
                );
            }
        }

        if (isset($data['tags'])) {
            $tagIds = collect($data['tags'])->map(fn($tag) =>
            Tag::firstOrCreate(['name' => $tag])->id
            );
            $translation->tags()->sync($tagIds);
        }

        return $translation->load(['values', 'tags']);
    }

    public function export(string $locale): array
    {
        $translations = Translation::with(['values' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->get();

        $result = [];

        foreach ($translations as $translation) {
            $value = $translation->values->first()?->value;
            if ($value) {
                $result[$translation->key] = $value;
            }
        }

        return $result;
    }
}
