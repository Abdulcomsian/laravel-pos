<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use DB;

class CartController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $getcustomerid = DB::table('user_cart')->first();
            if ($getcustomerid) {
                return response(
                    $request->user()->cart()->where('customer_id', $getcustomerid->customer_id)->get()
                );
            } else {
                return response($request->user()->cart()->get());
            }
        }
        $gstdata = Setting::where('key', 'gst')->first();
        return view('cart.index', compact('gstdata'));
    }

    public function getCart(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->cart()->where('customer_id', $request->customerid)->get()
            );
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;
        $table_id = $request->customerid;
        $product_id = $request->pid;
        $product = Product::where('barcode', $barcode)->first();
        $cart = DB::table('user_cart')->where(['product_id' => $product_id, 'customer_id' => $table_id])->first();
        //$cart = $request->user()->cart()->where(['product_id'=>$product_id,'customer_id'=>$table_id])->first();
        if ($cart) {
            // check product quantity
            if ($product->quantity <= $cart->quantity) {
                return response([
                    'message' => 'Product available only: ' . $product->quantity,
                ], 400);
            }
            // update only quantity
            DB::table('user_cart')->where(['product_id' => $product_id, 'customer_id' => $table_id])->update([
                'quantity' => DB::raw('quantity+1'),
            ]);
        } else {
            if ($product->quantity < 1) {
                return response([
                    'message' => 'Product out of stock',
                ], 400);
            }
            $request->user()->cart()->attach($product->id, ['quantity' => 1, 'customer_id' => $table_id]);
        }

        return response('', 204);
    }

    public function changeQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->user()->cart()->where('id', $request->product_id)->first();

        if ($cart) {
            $cart->pivot->quantity = $request->quantity;
            $cart->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->cart()->detach($request->product_id);

        return response('', 204);
    }

    public function empty(Request $request)
    {
        $request->user()->cart()->detach();

        return response('', 204);
    }
}
