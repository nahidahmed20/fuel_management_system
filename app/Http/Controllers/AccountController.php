<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:product view')->only('index');
    //     $this->middleware('permission:product create')->only('create','store');
    //     $this->middleware('permission:product edit')->only('edit','update');
    //     $this->middleware('permission:product delete')->only('destroy');
    // }
    public function index()
    {
        $accounts = Account::orderByDesc('id')->get();
        return view('deposit.index', compact('accounts'));
    }

    public function create()
    {
        return view('deposit.create');
    }

    // Store account data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric|min:0',
            'deposited_by' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        Account::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'deposited_by' => $request->deposited_by,
            'note' => $request->note,
        ]);

        return redirect()->route('account.index')->with('success', 'Account transaction added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('deposit.edit', compact('account'));
    }

    // Update account data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric|min:0',
            'deposited_by' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $account = Account::findOrFail($id);

        $account->update([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'deposited_by' => $request->deposited_by,
            'note' => $request->note,
        ]);

        return redirect()->route('account.index')->with('success', 'Account transaction updated successfully.');
    }

    // Delete account entry
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('account.index')->with('success', 'Account transaction deleted successfully.');
    }

}
