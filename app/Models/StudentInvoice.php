<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class StudentInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'invoice_sheet_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function invoiceSheet(): BelongsTo
    {
        return $this->belongsTo(InvoiceSheet::class);
    }
}
