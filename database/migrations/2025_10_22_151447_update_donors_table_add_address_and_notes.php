<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            // Add address and notes fields
            $table->text('address')->nullable()->after('phone');
            $table->text('notes')->nullable()->after('address');

            // Drop unique constraint from email and phone
            $table->dropUnique(['email']);
            $table->dropUnique(['phone']);

            // Add index instead (for performance, but not unique)
            $table->index('email');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            // Remove fields
            $table->dropColumn(['address', 'notes']);

            // Restore unique constraints
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            $table->unique('email');
            $table->unique('phone');
        });
    }
};
