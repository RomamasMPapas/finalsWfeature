@extends('master')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h2 class="text-primary font-weight-bold">My Delivery Orders</h2>
            <p class="text-muted">Track your orders and delivery status</p>
        </div>
    </div>

    @if(count($orders) > 0)
        <div class="row">
            @foreach($orders as $item)
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="{{ url('assets/images')}}/{{$item->gallery}}" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;" alt="{{$item->name}}">
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title font-weight-bold mb-1">{{$item->name}}</h5>
                                <p class="text-muted mb-2">Order ID: #{{$item->order_id}}</p>
                                <h6 class="text-primary font-weight-bold">${{$item->price}}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <small class="text-uppercase text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Status</small>
                                    <div>
                                        @if($item->delivery_status == 'delivered')
                                            <span class="badge badge-success px-3 py-2">Delivered</span>
                                        @elseif($item->delivery_status == 'cancelled')
                                            <span class="badge badge-danger px-3 py-2">Cancelled</span>
                                        @else
                                            <span class="badge badge-info px-3 py-2">In Progress</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Payment</small>
                                    <div>
                                        <span class="badge badge-light border px-2 py-1">{{ucfirst($item->payment_method)}}</span>
                                        <span class="badge {{ $item->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }} px-2 py-1">{{ucfirst($item->payment_status)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 border-left">
                                <div class="mb-2">
                                    <small class="text-muted"><i class="fas fa-key mr-1"></i> OTP Code</small>
                                    <h4 class="text-danger font-weight-bold letter-spacing-2">{{$item->otp}}</h4>
                                </div>
                                <div>
                                    <small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> Expected Delivery</small>
                                    <h6 class="text-dark font-weight-bold">{{\Carbon\Carbon::parse($item->delivery_date)->format('d M, Y')}}</h6>
                                </div>
                                
                                @if($item->delivery_status != 'cancelled' && $item->delivery_status != 'delivered')
                                <div class="mt-3">
                                    <a href="/cancel_order/{{$item->order_id}}" class="btn btn-outline-danger btn-sm btn-block" onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel Order
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-6 text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-box-open text-muted" style="font-size: 5rem;"></i>
                </div>
                <h3 class="text-muted mb-3">No orders found</h3>
                <p class="text-muted mb-4">Looks like you haven't placed any orders yet.</p>
                <a href="/" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">Start Shopping</a>
            </div>
        </div>
    @endif
</div>

@if(Session::has('payment_success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Order Received!',
            text: "{{ Session::get('payment_success') }}",
            icon: 'success',
            confirmButtonText: 'Track Order',
            confirmButtonColor: '#007bff'
        });
    });
</script>
@endif

<style>
    .letter-spacing-2 {
        letter-spacing: 2px;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection
