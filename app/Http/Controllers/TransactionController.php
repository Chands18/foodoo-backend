<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Requests\FoodRequest;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $transaction = Transaction::with(['food','user'])->paginate(10);
       
        return view('transactions.index', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function generatePDF(Request $request, $id)
    {
        // $order = Transaction::findOrFail($id);
        $order = Transaction::with(['food','user'])
        ->findOrFail($id);
       
        $pdf = PDF::loadView('orders.print', [
            'item' => $order
        ])
        ->setPaper('A8', 'portrait');
    
        return $pdf->stream('orderPrint.pdf', array("Attachment" => false));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function exportExcel()
	{
        $filename = urlencode("TranscationEcanteen".date("Y-m-d H:i:s").".xlsx");
		return Excel::download(new TransactionExport, $filename);
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.detail',[
            'item' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index');
    }

    public function changeStatus(Request $request, $id, $status, Food $food)
    {
        // Update Status Trx
        $transaction = Transaction::findOrFail($id);
        if($transaction->status != 'DELIVERED'){
            $transaction->status = $status;
            $transaction->save();
        }

        // Update Stock Food Delivered
        if($status === 'DELIVERED'){
            $food = Food::findOrFail($transaction->food_id);
            $updateStock = $food->stock - $transaction->quantity;
            $food->stock = $updateStock;
            $food->save();
      
        }

        return redirect()->route('transactions.show', $id);
    }
}
