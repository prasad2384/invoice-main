<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Invoice;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Invoice::where(function ($query) use ($request) {
            if ($request->id != '') {
                $query->where('id', $request->id);
            }
            if ($request->invoice_number != '') {
                $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            }
            if ($request->user_id != '') {
                // Join with the 'users' table to search by user name
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user_id . '%');  // Assuming 'name' is the column storing the user's name
                });
            }
            if ($request->total != '') {
                $query->where('total', $request->total);
            }
            if ($request->payment_method != '') {
                $query->where('payment_method', $request->payment_method);
            }
            if ($request->rate != '') {
                $query->where('rate', $request->rate);
            }
            if ($request->invoice_start_date != '' && $request->invoice_end_date != '') {
                $query->whereBetween('date', [$request->invoice_start_date, $request->invoice_end_date]);
            }
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.invoices.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $taxes = Tax::all();
        $invoice_number = DB::table('invoices')->latest('id')->first();
        $users = User::where('usertype', 'client')->get();

        return view('admin.invoices.create', compact('users', 'invoice_number', 'taxes'));
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
        $invoice_number = $request->get('invoice_number');
        $invoice_date = $request->get('invoice_date');
        $description = $request->get('description');
        $user_id = $request->get('user_id');
        $tax_id = $request->get('tax_id');
        $quantity = $request->get('quantity');
        $rate = $request->get('rate');
        $amount = $request->get('amount');
        $total = $request->get('total-amount');
        $payment_method = $request->get('payment_method');
        // Parse the provided invoice number


        // Extract the year and sequence from the provided invoice number
        $matches = [];
        preg_match('/^(\d{4}-\d{2})\/(\d+)$/', $invoice_number, $matches);

        if ($matches) {
            $yearPrefix  = $matches[1];
            $lastSequence = (int) $matches[2];

            // Check if the year from the invoice number is the current year
            $newSequence = $lastSequence + 1;
        } else {
            // If no matches, start with sequence 1
            $newSequence = 1;
        }

        // Format the new invoice number
        $invoice_number = "{$yearPrefix}/{$newSequence}";
        $invoice = Invoice::create([
            'invoice_number' => $invoice_number,
            'date' => $invoice_date,
            'description' => $description,
            'user_id' => $user_id,
            'quantity' => $quantity,
            'rate' => $rate,
            'tax_id' => $tax_id,
            'amount' => $amount,
            'payment_method' => $payment_method,
            'invoice_status_id' => 2,
            'total' => $total == 0 ? $amount : $total,
        ]);
        return redirect()->route('invoices.index')->with(['message' => 'New Invoice Created Successfully...']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        // return $id;
        $data = Invoice::where('id', $id)->first();
        return view('admin.invoices.view', compact('data'));
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
        $data = Invoice::where('id', $id)->first();
        $users = User::where('usertype', 'client')->get();
        $taxes = Tax::all();
        return view('admin.invoices.edit', compact('data', 'users', 'taxes'));
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
        // Invoice::where('id', $request->id)->update([
        //     'name' => $request->name,
        //     'status' => $request->status,
        // ]);
        $invoice = Invoice::find($id);
        $invoice_date = $request->get('invoice_date');
        $description = $request->get('description');
        $user_id = $request->get('user_id');
        $tax_id = $request->get('tax_id');
        $quantity = $request->get('quantity');
        $rate = $request->get('rate');
        $amount = $request->get('amount');
        $total = $request->get('total-amount');
        $payment_method = $request->get('payment_method');
        $data = [
            'date' => $invoice_date,
            'description' => $description,
            'user_id' => $user_id,
            'quantity' => $quantity,
            'rate' => $rate,
            'tax_id' => $tax_id,
            'amount' => $amount,
            'payment_method' => $payment_method,
            'invoice_status_id' => 2,
            'total' => $total == '' || $total == 0 ? $amount : $total,
        ];
        $invoice->update($data);
        return redirect()->route('invoices.index')->with(['message' => 'Invoice updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = Invoice::find($id);
        $data->delete();
        return redirect()->route('invoices.index')->with(['message' => 'Invoice deleted successfully']);
    }
}
