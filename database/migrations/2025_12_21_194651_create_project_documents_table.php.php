<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تشغيل الهجرة
     */
    public function up(): void
    {
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');

            // المعلومات الأساسية
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'contract',
                'tender',
                'approval',
                'drawing',
                'manual',
                'certificate',
                'invoice',
                'report',
                'photo',
                'other'
            ])->default('other');

            // الملف
            $table->string('file_name');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            // التواريخ
            $table->date('document_date')->nullable()->comment('تاريخ الوثيقة');
            $table->date('expiry_date')->nullable()->comment('تاريخ الانتهاء إن وجد');

            // التصنيف
            $table->enum('category', ['legal', 'technical', 'financial', 'administrative', 'other'])->default('technical');
            $table->enum('confidentiality', ['public', 'internal', 'confidential', 'secret'])->default('internal');

            // الإصدار
            $table->string('version')->nullable()->default('1.0');
            $table->boolean('is_latest')->default(true);

            // المراجعة
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['project_id', 'type']);
            $table->index(['uploaded_by', 'document_date']);
            $table->index('confidentiality');
            $table->index('expiry_date');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};