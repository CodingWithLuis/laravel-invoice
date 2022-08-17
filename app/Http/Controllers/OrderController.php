<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('products', 'customer')->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();

        $customers = Customer::all();

        return view('orders.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated() + [
            'user_id' => auth()->user()->id,
            'order_date' => now()
        ]);

        $products = $request->input('products', []);
        $quantities = $request->input('quantities', []);

        for ($product = 0; $product < count($products); $product++) {
            if ($products[$product] != '') {
                $order->products()->attach($products[$product], ['quantity' => $quantities[$product]]);
            }
        }

        return redirect()->route('orders.index');
    }

    public function generateInvoice(Order $order)
    {
        $customer = new Buyer([
            'name'          => $order->customer->name,
            'custom_fields' => [
                'email' => $order->customer->email,
            ],
        ]);

        $seller = new Buyer([
            'name'          => $order->user->name,
            'custom_fields' => [
                'email' => $order->user->email,
            ],
        ]);

        foreach ($order->products as $product) {
            $items[] = (new InvoiceItem())->title($product->name)
                ->pricePerUnit($product->price)
                ->quantity($product->pivot->quantity);
        }

        $invoice = Invoice::make()
            ->buyer($customer)
            ->seller($seller)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->taxRate(15)
            ->addItems($items);

        return $invoice->download();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
