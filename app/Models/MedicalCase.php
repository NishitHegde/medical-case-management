<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalCase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_number',
        'patient_reference',
        'surgeon_name',
        'implant_type',
        'status',
        'priority',
        'surgery_date',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surgery_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Allowed case statuses.
     */
    public const STATUSES = [
        'Draft',
        'Planning',
        'In Review',
        'Approved',
        'Completed',
        'Cancelled',
    ];

    /**
     * Allowed priorities.
     */
    public const PRIORITIES = [
        'Low',
        'Medium',
        'High',
    ];

    /**
     * Get the user who created the medical case.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias for creator relationship for consistency.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
