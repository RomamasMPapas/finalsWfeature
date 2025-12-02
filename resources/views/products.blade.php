@extends('master')
@section('content')
    <div class="container mt-0 mb-5">
        <div class="row">
            <div class="col-md-5 my-4" >
                {{-- <a href="product/{{$product['id']}}"> --}}
                    <div class=" product shadow-lg rounded bg-secondary product-details"  style=" border-color:rgb(230, 230, 248);">
                        <div class="image-hover">
                            <img class="card-img-top shadow rounded" id="product-image"src="{{ url('assets/images')}}/{{$product['gallery']}}"
                            alt="trending product" style="object-fit: cover; height : 355px; min-height : 280px;">
                        </div>
                    </div>
            </div>
            <div class="col-md-6 my-4 ">
                <div class="card-body shadow-lg rounded">
                    <a href="/"> continue shopping <i class="fa fa-arrow-right"></i> </a>
                    <h4 class="card-title text-secondary">{{$product['name']}}</h4>
                    <h5 class="text-secondary">Price : <span class="badge badge-pill badge-danger">{{$product['price']}}</span> </h5>
                    <h5 class="text-secondary card-text">Product Category : {{$product['category']}}</h5>
                    <p class="card-text text-secondary">Product Description : {{$product['description']}}</p>
                    <br><br><br>
                    <form action="{{route('add.cart')}}" method="POST">
                        @csrf
                        <input type="hidden" class="product_id" name="cart" value="{{$product['id']}}">
                        <button type="button" @if(Auth::check()) onclick="add_to_cart(this)" @else onclick="window.location.href='/login'"  @endif class="add-to-cart-btn my-3 btn btn-warning">Add to cart</button><br>
                    </form>
                    <a href="/checkout" class="btn d-none checkout-btn btn-success">Proceed to checkout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-secondary">Related Products</h3>
        </div>
        @foreach($related_products as $rel_product)
        <div class="col-md-3 my-4">
            <a href="{{$rel_product['id']}}">
                <div class="card shadow rounded" style="background-color: transparent; border-color:rgb(230, 230, 248);">
                   <div class="image-hover">
                     <img class="card-img-top rounded trending-image" height="250px" src="{{ url('assets/images')}}/{{$rel_product['gallery']}}" alt="related product">
                   </div>
                    <div class="card-body">
                        <h4 class="card-title text-secondary">{{$rel_product['name']}}</h4>
                        <p class="card-text">{{Str::limit($rel_product['description'], 50)}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection