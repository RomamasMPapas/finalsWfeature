@extends('master')
@section('content')
<style>
    .products-page {
        background: #f7fafc;
        min-height: 100vh;
        padding: 40px 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
        border-radius: 0 0 50px 50px;
    }
    
    .page-title {
        font-size: 48px;
        font-weight: 800;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .page-subtitle {
        font-size: 18px;
        opacity: 0.9;
        margin-top: 10px;
    }
    
    .category-section {
        margin-bottom: 60px;
    }
    
    .category-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 16px 16px 0 0;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .category-name {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    
    .category-count {
        margin-left: auto;
        background: rgba(255,255,255,0.2);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .products-grid {
        background: white;
        padding: 30px;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .product-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
    }
    
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 250px;
        background: #f8f9fa;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.1);
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .product-body {
        padding: 20px;
    }
    
    .product-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-description {
        color: #718096;
        font-size: 14px;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: 800;
        color: #667eea;
        margin: 0;
    }
    
    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .product-link:hover {
        text-decoration: none;
        color: inherit;
    }
    
    .empty-category {
        text-align: center;
        padding: 60px 20px;
        color: #718096;
    }
    
    .empty-category i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 32px;
        }
        
        .category-name {
            font-size: 20px;
        }
    }
</style>

<div class="products-page">
    <div class="page-header">
        <div class="container text-center">
            <h1 class="page-title"><i class="fas fa-store mr-3"></i>Our Products</h1>
            <p class="page-subtitle">Explore our collection organized by categories</p>
        </div>
    </div>

    <div class="container">
        @if($productsByCategory->count() > 0)
            @foreach($productsByCategory as $categoryName => $products)
                <div class="category-section">
                    <div class="category-header">
                        <div class="category-icon">
                            @if(strtolower($categoryName) == 'electronics' || strtolower($categoryName) == 'electronic')
                                <i class="fas fa-laptop"></i>
                            @elseif(strtolower($categoryName) == 'fashion' || strtolower($categoryName) == 'clothing' || strtolower($categoryName) == 'clothes')
                                <i class="fas fa-tshirt"></i>
                            @elseif(strtolower($categoryName) == 'accessories')
                                <i class="fas fa-watch"></i>
                            @elseif(strtolower($categoryName) == 'footwear' || strtolower($categoryName) == 'shoes')
                                <i class="fas fa-shoe-prints"></i>
                            @else
                                <i class="fas fa-shopping-bag"></i>
                            @endif
                        </div>
                        <h2 class="category-name">{{ ucfirst($categoryName) }}</h2>
                        <div class="category-count">
                            {{ $products->count() }} {{ $products->count() == 1 ? 'Product' : 'Products' }}
                        </div>
                    </div>
                    
                    <div class="products-grid">
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <a href="/product/{{ $product['id'] }}" class="product-link">
                                        <div class="product-card">
                                            <div class="product-image-wrapper">
                                                <img src="{{ url('assets/images')}}/{{ $product['gallery'] }}" 
                                                     alt="{{ $product['name'] }}" 
                                                     class="product-image">
                                                @if($product['quantity'] > 0)
                                                    <div class="product-badge">In Stock</div>
                                                @else
                                                    <div class="product-badge" style="background: #e53e3e;">Out of Stock</div>
                                                @endif
                                            </div>
                                            <div class="product-body">
                                                <h3 class="product-title">{{ $product['name'] }}</h3>
                                                <p class="product-description">{{ Str::limit($product['description'], 80) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="product-price">${{ number_format($product['price'], 2) }}</h4>
                                                    @if($product['quantity'] > 0 && $product['quantity'] <= 5)
                                                        <small class="text-danger">Only {{ $product['quantity'] }} left!</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="category-section">
                <div class="products-grid">
                    <div class="empty-category">
                        <i class="fas fa-box-open"></i>
                        <h3>No Products Available</h3>
                        <p>Check back later for new products!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
