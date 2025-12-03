@extends('master')
@section('content')
<style>
    .profile-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: calc(100vh - 56px);
        padding: 40px 0;
    }
    
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-header {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 30px;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #fff;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        flex-shrink: 0;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 32px;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 8px 0;
    }
    
    .profile-email {
        color: #718096;
        font-size: 16px;
        margin: 0;
    }
    
    .profile-stats {
        display: flex;
        gap: 30px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
        display: block;
    }
    
    .stat-label {
        font-size: 14px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .profile-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #f7fafc;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
    }
    
    .info-value {
        color: #718096;
        text-align: right;
    }
    
    .edit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .edit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .save-btn {
        background: #48bb78;
    }
    
    .cancel-btn {
        background: #718096;
        margin-left: 10px;
    }
    
    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background: #c6f6d5;
        color: #22543d;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-name {
            font-size: 24px;
        }
    }
</style>

<div class="profile-page">
    <div class="container profile-container">
        @if(Session::has('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ Session::get('success') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->first_name }} {{ $user->last_name }}</h1>
                <p class="profile-email"><i class="fas fa-envelope mr-2"></i>{{ $user->email }}</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $orderCount }}</span>
                        <span class="stat-label">Orders</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ date('M Y', strtotime($user->created_at)) }}</span>
                        <span class="stat-label">Member Since</span>
                    </div>
                </div>
            </div>
            <div>
                <button class="edit-btn" onclick="toggleEditMode()">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-md-6">
                <div class="profile-card">
                    <h2 class="card-title"><i class="fas fa-user mr-2"></i>Profile Information</h2>
                    
                    <div id="viewMode">
                        <div class="info-row">
                            <span class="info-label">First Name</span>
                            <span class="info-value">{{ $user->first_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Name</span>
                            <span class="info-value">{{ $user->last_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ $user->phone }}</span>
                        </div>
                    </div>

                    <div id="editMode" style="display: none;">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="info-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="info-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="info-label">Phone (11 digits)</label>
                                <input type="tel" name="phone" class="form-control" value="{{ $user->phone }}" pattern="[0-9]{11}" maxlength="11" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="edit-btn save-btn">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                                <button type="button" class="edit-btn cancel-btn" onclick="toggleEditMode()">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Address Card -->
            <div class="col-md-6">
                <div class="profile-card">
                    <h2 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i>Shipping Address</h2>
                    
                    <div id="addressViewMode">
                        <div class="info-row">
                            <span class="info-label">Address</span>
                            <span class="info-value">{{ $user->address }}</span>
                        </div>
                    </div>

                    <div id="addressEditMode" style="display: none;">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                            <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                            <input type="hidden" name="phone" value="{{ $user->phone }}">
                            <div class="form-group mb-3">
                                <label class="info-label">Full Address</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ $user->address }}</textarea>
                            </div>
                            <div>
                                <button type="submit" class="edit-btn save-btn">
                                    <i class="fas fa-save mr-2"></i>Save Address
                                </button>
                                <button type="button" class="edit-btn cancel-btn" onclick="toggleEditMode()">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="profile-card">
                    <h2 class="card-title"><i class="fas fa-cog mr-2"></i>Account Actions</h2>
                    <a href="/cartlist" class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-shopping-cart mr-2"></i>View Cart
                    </a>
                    <a href="/products" class="btn btn-outline-success btn-block mb-2">
                        <i class="fas fa-store mr-2"></i>Browse Products
                    </a>
                    <a href="/forgot-password" class="btn btn-outline-warning btn-block mb-2">
                        <i class="fas fa-key mr-2"></i>Forgot Password
                    </a>
                    <a href="/logout" class="btn btn-outline-danger btn-block">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    const addressViewMode = document.getElementById('addressViewMode');
    const addressEditMode = document.getElementById('addressEditMode');
    
    if (viewMode.style.display === 'none') {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        addressViewMode.style.display = 'block';
        addressEditMode.style.display = 'none';
    } else {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        addressViewMode.style.display = 'none';
        addressEditMode.style.display = 'block';
    }
}

// Phone number validation
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
    });
});
</script>
@endsection
