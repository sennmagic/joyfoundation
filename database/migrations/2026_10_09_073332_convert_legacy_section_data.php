<?php

declare(strict_types=1);

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rows seeded before the generic field names existed: statements stored
 * mission/vision/guides, image_text stored established_year, events stored cta_label,
 * and list sections had no limit (so they keep showing every item).
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('sections')->whereIn('type', ['statements', 'image_text', ...Section::LIST_TYPES])->get() as $row) {
            $data = json_decode($row->data, true);

            if ($row->type === 'statements' && ! isset($data['blocks'])) {
                $data['blocks'] = [
                    ['label' => '— '.($data['mission']['label'] ?? 'Mission'), 'segments' => $data['mission']['segments'] ?? []],
                    ['label' => '— '.($data['vision']['label'] ?? 'Vision'), 'segments' => $data['vision']['segments'] ?? []],
                    ['label' => $data['guides']['label'] ?? 'What Guides Us', 'segments' => $data['guides']['segments'] ?? []],
                ];
            }

            if ($row->type === 'image_text' && ! isset($data['badge_value']) && isset($data['established_year'])) {
                $data['badge_label'] = 'EST.';
                $data['badge_value'] = $data['established_year'];
            }

            if ($row->type === 'events' && ! isset($data['view_all_label']) && isset($data['cta_label'])) {
                $data['view_all_label'] = $data['cta_label'];
            }

            if (in_array($row->type, Section::LIST_TYPES, true) && ! array_key_exists('limit', $data)) {
                $data['limit'] = null;
            }

            DB::table('sections')->where('id', $row->id)->update(['data' => json_encode($data)]);
        }
    }

    public function down(): void
    {
        // Added keys are harmless to keep.
    }
};
