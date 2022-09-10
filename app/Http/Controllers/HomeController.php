<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getPayments()
    {

        $payments = [];
        $records = payment::get();
        foreach ($records as $key => $payment) {

            $paymentStatus = $payment->status == 1 ? '<span class="text-success">COMPLETED</span>' : '<span class="text-warning">PENDING</span>';
            $paymentAction = $payment->status == 1 ? '' : '<button class="btn-sm btn btn-success" onclick="approvePayment(' . $payment->id . ')">Approve</button>';

            $payments[] = [
                'no' => ++$key,
                'ref' => $payment->ref,
                'date' => date('d-m-Y', strtotime($payment->date)),
                'goal' => Goal::find($payment->goal)->ref,
                'user' => User::find($payment->user)->name,
                'status' => $paymentStatus,
                'action' => $paymentAction,
            ];
        }

        return $payments;
    }

    public function approvePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:payments,id',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'msg' => 'Invalid Request'
                ],
                400,
            );
        }

        payment::where('id', $request->id)->update(['status' => 1]);
        Goal::where('id', payment::find($request->id)->goal)->update(['payment_status' => 1]);
        return response(['msg' => 'Updated'], 200);
    }
}
