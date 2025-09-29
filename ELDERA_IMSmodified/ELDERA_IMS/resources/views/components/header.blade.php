<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<style>
    body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5faff;
        }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px 30px;
        position: fixed;
        top: 0;
        left: 250px; /* matches sidebar width */
        right: 0;
        height: 60px;
        z-index: 20;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-bottom: 1px solid #e0e0e0;
    }

    .dashboard-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        font-size: 18px;
    }

    .dashboard-icon {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 2px;
        width: 20px;
        height: 20px;
    }

    .dashboard-icon div {
        background: #555;
        border-radius: 2px;
    }

    .dashboard-text {
        font-weight: bold;
        color: #333;
        font-size: 18px;
        text-transform: uppercase;
    }

    .user-section {
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
    }

    .icon-button {
        width: 35px;
        height: 35px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .icon-button:hover {
        background: #f0f0f0;
    }

    .envelope-button {
        background: #555;
        color: white;
    }

    .user-icon {
        color: #555;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.2s;
    }

    

    .admin-group {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background-color 0.2s;
        position: relative;
    }

    .admin-group:hover {
        background: #f0f0f0;
    }

    .admin-text {
        color: #333;
        font-weight: 500;
        font-size: 14px;
    }

    /* Admin Dropdown Styles */
    .admin-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e0e0e0;
        min-width: 180px;
        z-index: 1001;
        display: none;
        margin-top: 5px;
    }

    .admin-dropdown.show {
        display: block;
    }

    .admin-dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #333;
        font-size: 14px;
    }

    .admin-dropdown-item:hover {
        background: #f8f9fa;
    }

    .admin-dropdown-item:first-child {
        border-radius: 8px 8px 0 0;
    }

    .admin-dropdown-item:last-child {
        border-radius: 0 0 8px 8px;
    }

    .admin-dropdown-item.logout {
        color: #dc3545;
        border-top: 1px solid #e0e0e0;
    }

    .admin-dropdown-item.logout:hover {
        background: #fff5f5;
    }


    body {
        background-color: ##fff5f5;
    }
    
</style>
</head>
<body>

    <div class="header">
        <div class="dashboard-title">
            @if(isset($attributes) && $attributes->has('icon'))
                <div class="page-icon">
                    <i class="{{ $attributes->get('icon') }}"></i>
                </div>
            @else
                <div class="dashboard-icon">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            @endif
            <span class="dashboard-text">{{ isset($attributes) ? $attributes->get('title', 'Dashboard') : 'Dashboard' }}</span>
        </div>
        
        
        <div class="user-section">
            <div class="admin-group" id="adminGroup">
                <i class="fas fa-user user-icon"></i>
                <span class="admin-text" id="adminText">Admin</span>
                
                <!-- Admin Dropdown -->
                <div class="admin-dropdown" id="adminDropdown">
                    <div class="admin-dropdown-item" onclick="window.location.href='{{ route('admin.profile') }}'">
                        <i class="fas fa-user-circle"></i>
                        Profile
                    </div>
                    <div class="admin-dropdown-item">
                        <i class="fas fa-cog"></i>
                        Settings
                    </div>
                        <div class="admin-dropdown-item logout" id="logoutItem">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </div>
                </div>
            </div>
        </div>
    </div>

    

    @if(isset($slot))
        {{$slot}}
    @endif

    <!-- Include popup message modal -->
    @include('message.popup_message')

    <script>
        

        class AdminDropdown {
            constructor() {
                this.init();
            }

            init() {
                this.bindEvents();
            }

            bindEvents() {
                const adminGroup = document.getElementById('adminGroup');
                const adminDropdown = document.getElementById('adminDropdown');
                const logoutItem = document.getElementById('logoutItem');

                adminGroup.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown();
                });

                logoutItem.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.handleLogout();
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.admin-group')) {
                        this.hideDropdown();
                    }
                });
            }

            toggleDropdown() {
                const dropdown = document.getElementById('adminDropdown');
                if (dropdown.classList.contains('show')) {
                    this.hideDropdown();
                } else {
                    this.showDropdown();
                }
            }

            showDropdown() {
                document.getElementById('adminDropdown').classList.add('show');
            }

            hideDropdown() {
                document.getElementById('adminDropdown').classList.remove('show');
            }

            handleLogout() {
                this.hideDropdown();
                // Use the custom confirmation modal
                showConfirmModal(
                    'Are you sure you want to logout?',
                    'You will be redirected to the login page.',
                    '{{ route("logout") }}',
                    'POST'
                );
            }
        }

        // Initialize components when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new AdminDropdown();
        });
    </script>
</body>
</html>