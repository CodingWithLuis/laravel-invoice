@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route("orders.store") }}" method="POST">
                @csrf


                <div class="card">
                    <div class="card-header">
                        Productos
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="customer_id" class="col-sm-1 col-form-label">Cliente</label>
                            <div class="col-sm-4">
                                <select name="customer_id" class="form-control">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }} ( {{ $customer->email }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <table class="table table-bordered mt-3" id="products_table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="product0">
                                    <td>
                                        <select name="products[]" class="form-control">
                                            <option value="">Seleccione un producto</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (${{ number_format($product->price, 2) }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantities[]" class="form-control" value="1" />
                                    </td>
                                </tr>
                                <tr id="product1"></tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="add_row" class="btn btn-success float-left">+ Agregar producto</button>
                                <button type="button" id='delete_row' class="float-right btn btn-danger">- Eliminar producto</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Guardar">
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        let row_number = 1;
        $("#add_row").click(function(e) {
            e.preventDefault();
            let new_row_number = row_number - 1;
            $('#product' + row_number).html($('#product' + new_row_number).html()).find('td:first-child');
            $('#products_table').append('<tr id="product' + (row_number + 1) + '"></tr>');
            row_number++;
        });

        $("#delete_row").click(function(e) {
            e.preventDefault();
            if (row_number > 1) {
                $("#product" + (row_number - 1)).html('');
                row_number--;
            }
        });
    });
</script>
@endsection
