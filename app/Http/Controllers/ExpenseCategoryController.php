<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ExpenseCategory::latest()->paginate(10);
        return view('expense-category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = ExpenseCategory::create([
            'name' => $request->name,
        ]);

        if (!$category) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating category.');
        }
        return redirect()->route('expensecategory.index')->with('success', 'Success, your category have been created.');
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
    public function edit(ExpenseCategory $expensecategory)
    {
        return view('expense-category.edit')->with('category', $expensecategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, ExpenseCategory $expensecategory)
    {
        $expensecategory->name = $request->name;

        if (!$expensecategory->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating Category.');
        }
        return redirect()->route('expensecategory.index')->with('success', 'Success, your cagtegory have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $expensecategory)
    {
        $expensecategory->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
