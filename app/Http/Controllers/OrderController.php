<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Transaction::with(['food','user'])
        ->where('transactions.status', '!=' , 'CANCELLED')
        ->where('transactions.status', '!=' , 'DELIVERED')
        ->paginate(10);
        return view('orders.index', [
            'order' => $order
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $order)
    {
        return view('orders.detail',[
            'item' => $order
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
    public function destroy(Transaction $order)
    {
        $order->delete();

        return redirect()->route('orders.index');
    }

    public function changeStatus(Request $request, $id, $status)
    {
        $order = Transaction::findOrFail($id);

        $order->status = $status;
        $order->save();

        return redirect()->route('order.show', $id);
    }
}
