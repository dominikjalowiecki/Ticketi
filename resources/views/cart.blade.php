@extends('layouts.ticketi')

@section('title', 'Cart')
@section('description', 'Cart page of Ticketi service')

@section('content')
  @include('shared.alerts')
  <div class="row">
      <div class="col">
        <h3 class="fw-bold">Cart</h3>
        <form method="POST" action="{{ route('cart.show') }}">
          @csrf
          @method('DELETE')
          <table class="table table-striped border my-4">
            <thead>
              <tr>
                <th width="5%" scope="col">#</th>
                <th width="45%" scope="col">Name</th>
                <th width="45%" scope="col">Cost</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
                <tr>
                  <th scope="row">1</th>
                  <td><a href="{{ route('event.page', [$item->url]) }}">{{ $item->name }}</a></td>
                  <td>{{ number_format($item->ticket_price, 2, '.', ',') }}&euro;</td>
                  <td>
                      <button
                      type="submit"
                      name="idEvent"
                      value="{{ $item->id_event }}"
                      class="btn-close"
                      aria-label="Remove item from cart"
                      ></button>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th scope="row"></th>
                <td class="fw-bold">Total</td>
                <td class="fw-bold" colspan="2">{{ number_format(get_cart_total(), 2, '.', ',') }}&euro;</td>
              </tr>
            </tfoot>
          </table>
        </form>
        <form method="POST" action="{{ route('cart.purchase-cart') }}">
          @csrf
          <p class="text-end">
            <button type="submit" class="btn btn-primary btn-lg {{ (get_cart_items_count() > 0) ? '' : 'disabled' }}">
              Buy
            </button>
          </p>
        </form>
      </div>
    </div>
@endsection