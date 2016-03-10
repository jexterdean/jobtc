<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class BillingController extends BaseController
{

    public function index($billing_type)
    {
        $data['billing_type'] = $billing_type;

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id');

        if (Entrust::hasRole('Admin')) {
            $billing = Billing::where('billing_type', '=', $billing_type)
                ->get();
        } elseif (Entrust::hasRole('Client')) {
            $billing = DB::table('fp_billing')
                ->join('fp_user', 'fp_user.client_id', '=', 'fp_billing.client_id')
                ->where('billing_type', '=', $billing_type)
                ->where('user_id', '=', Auth::user()->user_id)
                ->get();
        }

        $assets = ['table', 'datepicker'];

        return View::make('billing.index', [
            'data' => $data,
            'clients' => $client_options,
            'billings' => $billing,
            'assets' => $assets
        ]);
    }

    public function show($billing_type, $billing_id)
    {
        $data['billing_type'] = $billing_type;
        $data['billing_id'] = $billing_id;

        $billing = Billing::where('billing_id', '=', $billing_id)
            ->where('billing_type', '=', $billing_type)
            ->first();

        $client = Client::where('client_id', '=', $billing->client_id)
            ->first();

        $item = Item::where('billing_id', '=', $billing_id)
            ->get();

        $note = Note::where('belongs_to', '=', 'billing')
            ->where('unique_id', '=', $billing_id)
            ->where('username', '=', Auth::user()->username)
            ->first();

        $payments = Payment::where('billing_id', '=', $billing_id)
            ->get();

        $payment = Payment::where('billing_id', '=', $billing_id)
            ->sum('payment_amount');

        $assets = ['invoice', 'datepicker'];

        if ($billing)
            return View::make('billing.show', [
                'data' => $data,
                'billing' => $billing,
                'client' => $client,
                'items' => $item,
                'note' => $note,
                'payments' => $payments,
                'payment' => $payment,
                'assets' => $assets
            ]);
        else
            return Redirect::to('billing/' . $billing_type);
    }

    public function printing($billing_type, $billing_id)
    {
        $data['billing_type'] = $billing_type;
        $data['billing_id'] = $billing_id;

        if (Entrust::hasRole('Admin')) {
            $billing = Billing::where('billing_id', '=', $billing_id)
                ->where('billing_type', '=', $billing_type)
                ->first();
        } elseif (Entrust::hasRole('Client')) {
            $billing = Billing::where('billing_id', '=', $billing_id)
                ->join('fp_user', 'fp_user.client_id', '=', 'fp_billing.client_id')
                ->where('billing_type', '=', $billing_type)
                ->where('user_id', '=', Auth::user()->user_id)
                ->first();
        }

        $client = Client::where('client_id', '=', $billing->client_id)
            ->first();

        $item = Item::where('billing_id', '=', $billing_id)
            ->get();

        $note = Note::where('belongs_to', '=', 'billing')
            ->where('unique_id', '=', $billing_id)
            ->where('username', '=', Auth::user()->username)
            ->first();

        $payment = Payment::where('billing_id', '=', $billing_id)
            ->sum('payment_amount');

        $assets = ['invoice'];

        if ($billing) {
            // $pdf = PDF::loadView('billing.print', [
            // 	'data' => $data ,
            // 	'billing' => $billing,
            // 	'client' => $client,
            // 	'items' => $item,
            // 	'note' => $note,
            // 	'payment' => $payment,
            // 	'assets' => $assets
            // 	]);
            // return $pdf->download('invoice.pdf');

            ///////////// Thoujohn PDF
            // $pdf_data = [
            // 	'data' => $data ,
            // 	'billing' => $billing,
            // 	'client' => $client,
            // 	'items' => $item,
            // 	'note' => $note,
            // 	'payment' => $payment,
            // 	'assets' => $assets
            // 	];
            //  $html = View::make('billing.print', $pdf_data);
            // return PDF::load($html, 'A4', 'portrait')->show();
            ///////////// Thoujohn PDF end here

            return View::make('billing.print', [
                'data' => $data,
                'billing' => $billing,
                'client' => $client,
                'items' => $item,
                'note' => $note,
                'payment' => $payment,
                'assets' => $assets
            ]);
        } else
            return Redirect::to('billing/' . $billing_type);
    }

    public function create()
    {
    }

    public function edit($billing_type, $billing_id)
    {
        $data['billing_type'] = $billing_type;
        $data['billing_id'] = $billing_id;

        $client_options = Client::orderBy('company_name', 'asc')
            ->lists('company_name', 'client_id');

        $billing = Billing::where('billing_id', '=', $billing_id)
            ->where('billing_type', '=', $billing_type)
            ->first();

        if (count($billing))
            return View::make('billing.edit', [
                'data' => $data,
                'billing' => $billing,
                'clients' => $client_options
            ]);
        else
            return Redirect::to('billing/' . $billing_type);
    }

    public function store()
    {

        $validationRule = [
            'ref_no' => 'required|unique:fp_billing',
            'billing_type' => 'required|in:invoice,estimate',
            'client_id' => 'required',
            'issue_date' => 'required|date_format:"d-m-Y"',
            'currency' => 'required',
            'tax' => 'required|numeric'
        ];

        if (Input::get('billing_type') == 'invoice') {
            $validationRule['due_date'] = 'required|date_format:"d-m-Y"|after:issue_date';
            $validationRule['discount'] = 'required|numeric';
        } elseif (Input::get('billing_type') == 'estimate') {
            $validationRule['valid_date'] = 'required|date_format:"d-m-Y"|after:issue_date';
        }

        $validation = Validator::make(Input::all(), $validationRule);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $billing = new Billing;
        $billing->ref_no = Input::get('ref_no');
        $billing->billing_type = Input::get('billing_type');
        $billing->client_id = Input::get('client_id');
        $billing->issue_date = date("Y-m-d H:i:s", strtotime(Input::get('issue_date')));
        if (Input::get('billing_type') == 'invoice')
            $billing->due_date = date("Y-m-d H:i:s", strtotime(Input::get('due_date')));
        if (Input::get('billing_type') == 'estimate')
            $billing->valid_date = date("Y-m-d H:i:s", strtotime(Input::get('valid_date')));
        $billing->currency = Input::get('currency');
        $billing->tax = Input::get('tax');
        $billing->discount = Input::get('discount');
        $billing->notes = Helper::mynl2br(Input::get('notes'));
        $billing->save();
        $billing_id = $billing->billing_id;
        return Redirect::to('billing/' . Input::get('billing_type') . '/' . $billing_id)->withSuccess("Added successfully!!");
    }

    public function update($billing_id)
    {

        $validationRule = [
            'ref_no' => 'required|unique:fp_billing,ref_no,' . $billing_id . ',billing_id',
            'billing_type' => 'required|in:invoice,estimate',
            'client_id' => 'required',
            'issue_date' => 'required|date_format:"d-m-Y"',
            'currency' => 'required',
            'tax' => 'required|numeric'
        ];

        if (Input::get('billing_type') == 'invoice') {
            $validationRule['due_date'] = 'required|date_format:"d-m-Y"|after:issue_date';
            $validationRule['discount'] = 'required|numeric';
        } elseif (Input::get('billing_type') == 'estimate') {
            $validationRule['valid_date'] = 'required|date_format:"d-m-Y"|after:issue_date';
        }

        $validation = Validator::make(Input::all(), $validationRule);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $billing = Billing::find($billing_id);
        $billing->ref_no = Input::get('ref_no');
        $billing->billing_type = Input::get('billing_type');
        $billing->client_id = Input::get('client_id');
        $billing->issue_date = date("Y-m-d H:i:s", strtotime(Input::get('issue_date')));
        if (Input::get('billing_type') == 'invoice')
            $billing->due_date = date("Y-m-d H:i:s", strtotime(Input::get('due_date')));
        if (Input::get('billing_type') == 'estimate')
            $billing->valid_date = date("Y-m-d H:i:s", strtotime(Input::get('valid_date')));
        $billing->currency = Input::get('currency');
        $billing->tax = Input::get('tax');
        $billing->discount = Input::get('discount');
        $billing->notes = Helper::mynl2br(Input::get('notes'));
        $billing->save();
        $billing_id = $billing->billing_id;

        return Redirect::to('billing/' . Input::get('billing_type') . '/' . $billing_id)->withSuccess("Added successfully!!");
    }

    public function destroy()
    {
    }

    public function delete($billing_id)
    {
        $billing = Billing::find($billing_id);

        if (!$billing || !Entrust::hasRole('Admin'))
            return Redirect::back()->withErrors('This is not a valid link!!');

        DB::table('fp_item')
            ->where('billing_id', '=', $billing_id)
            ->delete();

        DB::table('fp_payment')
            ->where('billing_id', '=', $billing_id)
            ->delete();

        $return_url = $billing->billing_type;
        $billing->delete();

        return Redirect::to('billing/' . $return_url)->withSuccess('Delete Successfully!!!');

    }
}

?>