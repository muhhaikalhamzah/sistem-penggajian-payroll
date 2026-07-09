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
        Schema::table('payslips', function (Blueprint $table) {
            $table->decimal('basic_salary', 15, 2)->after('period')->default(0);
            $table->decimal('allowance_total', 15, 2)->after('basic_salary')->default(0);
            $table->decimal('deduction_total', 15, 2)->after('allowance_total')->default(0);
            $table->decimal('pph21_tax', 15, 2)->after('deduction_total')->default(0);
            $table->decimal('bpjs_fee', 15, 2)->after('pph21_tax')->default(0);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft')->after('net_salary');
            
            $table->dropColumn(['gross_salary', 'total_deductions']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'basic_salary', 
                'allowance_total', 
                'deduction_total', 
                'pph21_tax', 
                'bpjs_fee', 
                'status'
            ]);
            $table->decimal('gross_salary', 15, 2)->after('period');
            $table->decimal('total_deductions', 15, 2)->after('gross_salary');
        });
    }
};
