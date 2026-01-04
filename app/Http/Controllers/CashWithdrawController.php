<?php

namespace App\Http\Controllers;

use App\Models\CashWithdraw;
use Illuminate\Http\Request;

class CashWithdrawController extends Controller
{
    public function index()
    {
        $withdraws = CashWithdraw::latest()->get();
        return view('withdraw.index', compact('withdraws'));
    }

    public function create()
    {
        return view('withdraw.create');
    }

    public function store(Request $request)
    {
        CashWithdraw::create($request->all());
        return redirect()->route('cash.withdraw.index')->with('success', 'Withdraw entry created.');
    }

    public function edit($id)
    {
        $withdraw = CashWithdraw::findOrFail($id);
        return view('withdraw.edit', compact('withdraw'));
    }

    public function update(Request $request, $id)
    {
        $withdraw = CashWithdraw::findOrFail($id);
        $withdraw->update($request->all());
        return redirect()->route('cash.withdraw.index')->with('success', 'Withdraw entry updated.');
    }

    public function destroy($id)
    {
        CashWithdraw::findOrFail($id)->delete();
        return redirect()->route('cash.withdraw.index')->with('success', 'Withdraw entry deleted.');
    }

}
