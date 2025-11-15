<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (! Schema::hasColumn('sales', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('client_id')
                    ->constrained()
                    ->cascadeOnDelete();
            }
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if ($firstUserId) {
            DB::table('clients')
                ->whereNull('user_id')
                ->update(['user_id' => $firstUserId]);

            $clientOwners = DB::table('clients')->pluck('user_id', 'id');

            DB::table('sales')
                ->whereNull('user_id')
                ->orderBy('id')
                ->chunkById(100, function ($sales) use ($clientOwners, $firstUserId) {
                    foreach ($sales as $sale) {
                        $ownerId = $clientOwners[$sale->client_id] ?? $firstUserId;
                        DB::table('sales')
                            ->where('id', $sale->id)
                            ->update(['user_id' => $ownerId]);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
