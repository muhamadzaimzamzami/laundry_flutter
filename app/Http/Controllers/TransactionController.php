<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\transaction;

class TransactionController extends Controller
{
    //get all transaction
    public function index(){
        return response([
            'transaction' => transaction::orderBy('created_at', 'desc')->with('user:id,name')->get()
        ],200);
    }

    //get single transaction
    public function show($id){
        return response([
            'trans' => transaction::where('id', $id)->get()
        ],200);
    }

    //create transaction
    public function store(Request $request){
        //validate field
        $attrs = $request->validate([
            'nameCustomer' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'weight' => 'required',
            'paket' => 'required',
            'total' => 'required'
        ]);
        
        $create = transaction::create([
            'user_id' => auth()->user()->id,
            'nameCustomer' => $attrs['nameCustomer'],
            'phone' => $attrs['phone'],
            'address' => $attrs['address'],
            'weight' => $attrs['weight'],
            'paket' => $attrs['paket'],
            'total' => $attrs['total'],
        ]);

        return response([
            'message' => 'Transaction Saved',
            'transaction' => $create
        ], 200);
    }

    //update transaction
    public function update(Request $request, $id){
        $trans = transaction::find($id);

        if(!$trans){
            return response([
                'message' => 'Transactiont not found'
            ], 403);
        }

        if ($trans->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permision Denied'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'status' => 'required'
        ]);

        $trans->update([
            'status' => $attrs['status']
        ]);

        return response([
            'message' => 'Transaction Finished',
            'trans' => $trans
        ], 200);
    }

    //delete transaction
    public function destroy($id){
        $trans = transaction::find($id);
        if(!$trans){
            return response([
                'message' => 'Transaction not found'
            ], 403);
        }

        if ($trans->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permision Denied'
            ], 403);
        }

        $trans->delete();
        return response([
            'message' => 'Transaction Deleted'
        ], 200);
    }
}
