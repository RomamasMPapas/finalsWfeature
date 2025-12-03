@extends('master')
@section('content')
<style>
    .checkout-container {
        background-color: #f8f9fa;
        padding: 40px 0;
        min-height: 80vh;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 20px;
        border-radius: 12px 12px 0 0 !important;
    }
    .card-title {
        margin-bottom: 0;
        font-weight: 700;
        color: #333;
    }
    .form-control {
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e0e0e0;
    }
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        border-color: #007bff;
    }
    .payment-option {
        border: 2px solid #eee;
        border-radius: 10px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 10px;
    }
    .payment-option:hover {
        border-color: #007bff;
        background-color: #f8fbff;
    }
    .payment-option.selected {
        border-color: #007bff;
        background-color: #f0f7ff;
    }
    .form-check-input {
        cursor: pointer;
    }
    .order-summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        color: #555;
    }
    .total-row {
        border-top: 2px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: 700;
        font-size: 1.2rem;
        color: #333;
    }
    .btn-order {
        padding: 15px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .btn-order:hover {
        transform: translateY(-2px);
    }
    .product-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 15px;
    }
    .item-details {
        display: flex;
        align-items: center;
    }
</style>

<div class="checkout-container">
    <div class="container">
        <h2 class="mb-4 text-center font-weight-bold text-secondary">Checkout</h2>
        
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        <form action="{{ route('pay') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row">
                <!-- Left Column: Shipping & Payment -->
                <div class="col-lg-8">
                    <!-- Shipping Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-shipping-fast mr-2 text-primary"></i>Shipping Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{$user_data['first_name']}}" readonly>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{$user_data['last_name']}}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="{{$user_data['email']}}" readonly>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" value="{{$user_data['phone']}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Delivery Address</label>
                                <textarea class="form-control" name="address" rows="3" readonly>{{$user_data['address']}}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-credit-card mr-2 text-success"></i>Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-option selected" onclick="selectPayment('cod')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                                    <label class="form-check-label" for="payment_cod">
                                        <i class="fas fa-money-bill-wave text-success mr-2"></i> <strong>Cash on Delivery</strong>
                                        <p class="text-muted mb-0 small ml-4">Pay with cash upon delivery.</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-option" onclick="selectPayment('card')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                    <label class="form-check-label" for="payment_card">
                                        <i class="far fa-credit-card text-primary mr-2"></i> <strong>Credit / Debit Card</strong>
                                        <p class="text-muted mb-0 small ml-4">Secure payment via Stripe.</p>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-option" onclick="selectPayment('paypal')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                                    <label class="form-check-label" for="payment_paypal">
                                        <i class="fab fa-paypal text-info mr-2"></i> <strong>PayPal</strong>
                                        <p class="text-muted mb-0 small ml-4">Fast and secure payment.</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title text-white"><i class="fas fa-shopping-cart mr-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($products as $product)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="item-details">
                                        <img src="{{ url('assets/images')}}/{{$product->gallery}}" class="product-thumbnail" alt="{{$product->name}}">
                                        <div>
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{$product->name}}</h6>
                                            <small class="text-muted">Qty: 1</small>
                                        </div>
                                    </div>
                                    <span class="font-weight-bold">${{$product->price}}</span>
                                </div>
                                @endforeach
                            </div>
                            
                            <hr>
                            
                            <div class="order-summary-item">
                                <span>Subtotal</span>
                                <span>${{$total}}</span>
                            </div>
                            <div class="order-summary-item">
                                <span>Delivery Fee</span>
                                <span class="text-success">Free</span>
                            </div>
                            <div class="order-summary-item">
                                <span>Tax (Estimate)</span>
                                <span>$10.00</span>
                            </div>
                            
                            <div class="order-summary-item total-row">
                                <span>Total</span>
                                <span class="text-primary">${{$total + 10}}</span>
                            </div>

                            <input type="hidden" name="amount" value="{{$total + 10}}">
                            
                            <button type="submit" class="btn btn-success btn-block btn-order mt-4 shadow">
                                Place Order <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function selectPayment(value) {
        // Update radio button
        document.getElementById('payment_' + value).checked = true;
        
        // Update visual styling
        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('selected');
        });
        document.getElementById('payment_' + value).closest('.payment-option').classList.add('selected');
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card' || paymentMethod === 'paypal') {
            Swal.fire({
                title: 'Enter OTP',
                input: 'text',
                inputValue: '',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Verify & Pay',
                showLoaderOnConfirm: true,
                preConfirm: (otp) => {
                    if (!otp) {
                        Swal.showValidationMessage('Please enter OTP');
                    }
                    return otp;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // In a real app, we would verify the OTP here via AJAX.
                    // For now, we assume it's correct and submit the form.
                    this.submit();
                }
            });
        } else {
            // Cash on Delivery - submit directly
            this.submit();
        }
    });
</script>
@endsection