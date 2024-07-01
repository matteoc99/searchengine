<?php

use App\Models\SiteStage;
use App\Models\SiteStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('url_hash', 64)->unique();
            $table->string('url', 512);
            $table->string('canonical', 512)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('author', 255)->nullable();
            $table->string('keywords', 2048)->nullable();
            $table->unsignedTinyInteger('stage')->default(SiteStage::PENDING);
            $table->unsignedTinyInteger('status')->default(SiteStatus::UNKNOWN);
            $table->unsignedSmallInteger('http_code')->nullable();
            $table->timestamps();
            // Indexes
            $table->index('url_hash');
            $table->index(['status', 'url']);
        });

        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->json('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
