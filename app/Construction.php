<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'contact_id',
        'commission_percentage',
        'introducer_id',
        'total_payment',
        'advance_payment'
    ];

    public static function forDropdown($prepend_none = true, $prepend_all = false)
    {
        $constructions = self::all()->pluck('name', 'id');

        if ($prepend_none) {
            $constructions = $constructions->prepend(__('lang_v1.none'), '');
        }

        if ($prepend_all) {
            $constructions = $constructions->prepend(__('lang_v1.all'), '');
        }

        return $constructions;
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
    public function introducer()
    {
        return $this->belongsTo(Contact::class, 'introducer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_construction');
    }

    public function getTotalTransactions()
    {
        return $this->transactions()->sum('final_total');
    }

    public function getTotalAdvancePayments()
    {
        return $this->transactions()->where('payment_type', 'advance')->sum('final_total');
    }

    public function getTotalUnpaidAmount()
    {
        return $this->transactions()->where('payment_status', '!=', 'paid')->sum('final_total');
    }

    public function getTotalPaidAmount()
    {
        return $this->transactions()->where('payment_status', 'paid')->sum('final_total');
    }

    public function getTransactionsSummary()
    {
        return [
            'total' => $this->getTotalTransactions(),
            'advance_payments' => $this->getTotalAdvancePayments(),
            'unpaid_amount' => $this->getTotalUnpaidAmount(),
            'paid_amount' => $this->getTotalPaidAmount(),
        ];
    }

    public function getTotalAdvanceAttribute()
    {
        return $this->transactions()
            ->where('payment_status', 'advance')
            ->sum('final_total');
    }

    /**
     * Get total unpaid amount for the construction.
     */
    public function getTotalUnpaidAttribute()
    {
        return $this->transactions()
            ->where('payment_status', 'unpaid')
            ->sum('final_total');
    }

    /**
     * Get total paid amount for the construction.
     */
    public function getTotalPaidAttribute()
    {
        return $this->transactions()
            ->where('payment_status', 'paid')
            ->sum('final_total');
    }
}
