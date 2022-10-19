<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            return response(
                $request->user()->cart()->where('customer_id',001)->get()
            );
        }
        return view('cart.index');
    }

    public function getCart(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->cart()->where('customer_id',$request->customerid)->get()
            );
        }
       

    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;
        $table_id=$request->customerid;
        $product = Product::where('barcode', $barcode)->first();
        $cart = $request->user()->cart()->where('barcode', $barcode)->where('customer_id',$table_id)->first();
        if ($cart) {
            // check product quantity
            if($product->quantity <= $cart->pivot->quantity) {
                return response([
                    'message' => 'Product available only: '. $product->quantity,
                ], 400);
            }
            // update only quantity
            $cart->pivot->quantity = $cart->pivot->quantity + 1;
            $cart->pivot->save();
        } else {
            if($product->quantity < 1) {
                return response([
                    'message' => 'Product out of stock',
                ], 400);
            }
            $request->user()->cart()->attach($product->id, ['quantity' => 1,'customer_id'=>$table_id]);
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
