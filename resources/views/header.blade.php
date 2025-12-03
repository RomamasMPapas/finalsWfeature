<?php
    use App\Http\Controllers\ProductController;
    use Illuminate\Support\Facades\Auth;
    $total_items = 0;
    if (Auth::check()) {
        $total_items = ProductController::CartNum();   
    }
?>
<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand" href="/">E-commerce</a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/products">Products</a>
            </li>
            <form class="form-inline my-2 my-lg-0 ml-3" action="/search">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </ul>
        
        <ul class="navbar-nav ml-auto">
            @if(Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle mr-1"></i> {{Auth::user()->first_name}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="/profile"><i class="fas fa-user mr-2"></i>My Profile</a>
                    <a class="dropdown-item" href="/delivery"><i class="fas fa-truck mr-2"></i>Delivery</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                </div>
            </li>
            <li class="nav-item ml-2">
                <a class="btn btn-outline-light position-relative" href="/cartlist">
                    <i class="fas fa-shopping-cart"></i>
                    @if($total_items > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{$total_items}}
                    </span>
                    @endif
                </a>
            </li>
            @else
            <li class="nav-item">
                <a class="btn btn-outline-light mr-2" href="/login">Login</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-light" href="/register">Register</a>
            </li>
            @endif
        </ul>
    </div>
</nav>