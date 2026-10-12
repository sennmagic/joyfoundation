<?php

declare(strict_types=1);

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Section::query()->firstOrCreate(['page_id' => null, 'type' => 'contact'], ['data' => []]);
    }

    public function down(): void
    {
        // Keep contact details that an editor may have entered since the migration.
    }
};
