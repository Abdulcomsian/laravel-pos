<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function income(Request $request)
    {
        $data = new OrderItem();
        if ($request->start_date) {
            $data = $data->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $data = $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if ($request->start_date == "" && $request->end_date == "") {
            $data = $data->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        $data = $data->with('order.customer', 'order.user', 'order.payments', 'product')->get();
        return view('reports.income', compact('data'));
    }

    public function Profitlost(Request $request)
    {

        //work for income
        $data = new OrderItem();
        $expensedata = new Expense();
        if ($request->start_date) {
            $data = $data->where('created_at', '>=', $request->start_date);
            $expensedata = $expensedata->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $data = $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
            $expensedata = $expensedata->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if ($request->start_date == "" && $request->end_date == "") {
            $data = $data->where('created_at', '>', now()->subDays(30)->endOfDay());
            $expensedata = $expensedata->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        $data = $data->with('order.customer', 'order.user', 'product')->get();
        $expensedata = $expensedata->with('category')->get();
        return view('reports.income-expense', compact('data', 'expensedata'));
    }
}
