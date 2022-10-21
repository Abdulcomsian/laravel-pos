<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Models\ExpenseCategory;
use App\Models\Expense;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::with('category')->latest()->paginate(20);
        return view('expense.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = ExpenseCategory::get();
        return view('expense.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create([
            'amount' => $request->amount,
            'expense_categorie_id' => $request->expense_categorie_id,
            'note' => $request->note,
        ]);

        if (!$expense) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating expense.');
        }
        return redirect()->route('expense.index')->with('success', 'Success, your expense have been created.');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $category = ExpenseCategory::get();
        return view('expense.edit', compact('expense', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->amount = $request->amount;
        $expense->expense_categorie_id = $request->expense_categorie_id;
        $expense->note = $request->note;

        if (!$expense->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating Expense.');
        }
        return redirect()->route('expense.index')->with('success', 'Success, your Expense have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
