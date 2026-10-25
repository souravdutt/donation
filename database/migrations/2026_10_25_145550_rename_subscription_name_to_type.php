<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->renameColumn('name', 'type');
        });
        Schema::table('subscription_items', function (Blueprint $table): void {
            $table->dropUnique(['subscription_id', 'stripe_price']);
            $table->index(['subscription_id', 'stripe_price']);
        });
    }

    public function down(): void
    {
        Schema::table('subscription_items', function (Blueprint $table): void {
            $table->dropIndex(['subscription_id', 'stripe_price']);
            $table->unique(['subscription_id', 'stripe_price']);
        });
        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->renameColumn('type', 'name');
        });
    }
};
