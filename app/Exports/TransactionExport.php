<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            '#',
            'User',
            'Food',
            'Order Date',
            'Qty',
            'Total',
            'Seller',
            'Status',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      
        $arrSelect = [
            'transactions.id',
            'users.name as nameUser',
            'food.name as foodName',
            'transactions.created_at as orderDate',
            'transactions.quantity as quantity',
            'transactions.total as total',
            'sellers.name as serllerName',
            'transactions.status as status',
        ];
        $data = DB::table('users')
        ->join('transactions', 'users.id', '=', 'transactions.user_id')
        ->join('food', 'food.id', '=', 'transactions.food_id')
        ->join('sellers', 'sellers.id', '=', 'food.seller_id')
        ->select($arrSelect)
        ->get();

        return $data;

        // return Transaction::awith(['food','user']);
        // return Transaction::all();
    }
}
