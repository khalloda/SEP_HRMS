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
        Schema::create('payslip_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payslip_id')->constrained()->onDelete('cascade');
            $table->foreignId('salary_component_id')->constrained()->onDelete('cascade');
            $table->string('component_name', 100);
            $table->enum('component_type', ['earning', 'deduction', 'info']);
            $table->string('calculation_mode', 50)->nullable();
            $table->string('formula', 500)->nullable();
            $table->decimal('rate', 15, 4)->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('priority')->default(0);
            $table->boolean('is_taxable')->default(false);
            $table->text('calculation_notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('component_type');
            $table->index('priority');
            $table->index(['payslip_id', 'component_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_lines');
    }
};
