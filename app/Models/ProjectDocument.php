<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'title',
        'description',
        'type',
        'file_name',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'document_date',
        'expiry_date',
        'category',
        'confidentiality',
        'version',
        'is_latest',
        'reviewed_by',
        'reviewed_at',
        'review_notes'
    ];

    protected $casts = [
        'document_date' => 'date',
        'expiry_date' => 'date',
        'reviewed_at' => 'datetime',
        'file_size' => 'integer',
        'is_latest' => 'boolean',
    ];

    /**
     * العلاقات
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * النطاقات
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeConfidential($query)
    {
        return $query->where('confidentiality', 'confidential')->orWhere('confidentiality', 'secret');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    /**
     * التوابع المساعدة
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function isConfidential()
    {
        return in_array($this->confidentiality, ['confidential', 'secret']);
    }

    public function isReviewed()
    {
        return !is_null($this->reviewed_by);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * إدارة الملفات
     */
    public function download()
    {
        if (!auth()->user()->can('view projects')) {
            abort(403, 'غير مصرح لك بتنزيل هذا المستند');
        }

        if (!file_exists(storage_path('app/public/' . $this->file_path))) {
            abort(404, 'الملف غير موجود');
        }

        return response()->download(
            storage_path('app/public/' . $this->file_path),
            $this->original_name
        );
    }

    /**
     * الأحداث
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (!$document->version) {
                $document->version = '1.0';
            }

            if (!$document->is_latest) {
                $document->is_latest = true;
            }

            if (!$document->document_date) {
                $document->document_date = now();
            }
        });
    }
}