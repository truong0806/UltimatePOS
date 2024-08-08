<?php

namespace App\Utils;

use App\Construction;
use App\Transaction;
use DB;

class ConstructionUtil extends Util
{
    /**
     * Returns Walk In Customer for a Business
     *
     * @param  int  $business_id
     * @return array/false
     */
    public function getConstructionInfo($business_id, $construction_id)
    {
        $construction = Construction::where('constructions.id', $construction_id)
            ->leftjoin('transactions AS t', 'constructions.id', '=', 't.construction_id')
            ->leftJoin(
                'contacts AS introducer',
                'constructions.introducer_id',
                '=',
                'introducer.id'
            )
            ->leftJoin(
                'contacts AS customer',
                'constructions.contact_id',
                '=',
                'customer.id'
            )
            ->select(
                DB::raw('IFNULL(introducer.name, "") as introducer_name'),
                DB::raw('IFNULL(customer.name, "") as customer_name'),
                DB::raw('IFNULL(customer.contact_id, "") as customer_code'),
                DB::raw('IFNULL(introducer.contact_id, "") as introducer_code'),
                DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
                'constructions.*'
            )->first();

        return $construction;
    }
}
