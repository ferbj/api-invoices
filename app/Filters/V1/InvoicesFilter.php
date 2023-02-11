<?php
namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class InvoicesFilter extends ApiFilter
{
    //eq = equals to, gt= greather than, lt = lower than
    protected $safeParams = [
        'customer_id' => ['eq'],
        'amount' => ['eq','lt','gt','lte','gte'],
        'status' => ['eq'],
        'billedDate' => ['eq','lt','gt','lte','gte'],
        'paidDate' => ['eq','lt','gt','lte','gte']
    ];

    protected $columnMap = [
        'customerId' => 'customer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date'
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];

    public function transform(Request $request)
    {
        $eloQuery = [];
        foreach ($this->safeParams as $parm => $operators) {
            $query = $request->query($parm);
            if (!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$parm] ?? $parm;
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column,$this->operatorMap[$operator],$query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
