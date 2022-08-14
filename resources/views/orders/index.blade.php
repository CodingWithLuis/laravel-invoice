@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">

                    <a href="{{ route('orders.create') }}" class="btn btn-primary">Nueva orden</a>

                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de la Orden</th>
                                <th>Cliente</th>
                                <th>Email Cliente</th>
                                <th>Productos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr data-entry-id="{{ $order->id }}">
                                <td>
                                    {{ $order->id ?? '' }}
                                </td>
                                <td>
                                    {{ $order->order_date ?? '' }}
                                </td>
                                <td>
                                    {{ $order->customer->name ?? '' }}
                                </td>
                                <td>
                                    {{ $order->customer->email ?? '' }}
                                </td>
                                <td>
                                    <ul>
                                        @foreach($order->products as $item)
                                        <li>{{ $item->name }} ({{ $item->pivot->quantity }} x ${{ $item->price }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-danger">Generar Factura</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
