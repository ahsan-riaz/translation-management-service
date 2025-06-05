<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;
use App\Models\TranslationValue;
use Illuminate\Support\Str;

class SeedTranslations extends Command
{
    protected $signature = 'seed:translations {count=100000}';
    protected $description = 'Seed translations and values for performance testing';

    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("Seeding {$count} translations...");

        $batchSize = 1000;

        for ($i = 0; $i < $count; $i += $batchSize) {
            $translations = [];

            for ($j = 0; $j < $batchSize && $i + $j < $count; $j++) {
                $key = 'key_' . Str::random(10);
                $translations[] = ['key' => $key, 'group' => 'group_' . rand(1, 10), 'created_at' => now(), 'updated_at' => now()];
            }

            Translation::insert($translations);
        }

        $this->info("Seeding translation values...");

        // Attach 2 locales per translation
        Translation::chunk(1000, function ($translations) {
            $values = [];
            foreach ($translations as $t) {
                $values[] = [
                    'translation_id' => $t->id,
                    'locale' => 'en',
                    'value' => 'Value EN ' . $t->key,
                ];
                $values[] = [
                    'translation_id' => $t->id,
                    'locale' => 'fr',
                    'value' => 'Value FR ' . $t->key,
                ];
            }
            TranslationValue::insert($values);
        });

        $this->info("Seeding completed.");
    }
}
