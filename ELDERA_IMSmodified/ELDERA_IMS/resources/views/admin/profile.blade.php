<x-sidebar>
<x-header title="Admin Profile" icon="fas fa-user-circle">
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 80px);
            margin-left: 280px; /* Account for sidebar width */
            margin-top: 80px; /* Account for header height */
        }

        .profile-header {
            background: #e31575;
            /* background: linear-gradient(135deg, #e31575 0%, #ffb7ce 100%); */
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 20px;
            border: 4px solid rgba(255,255,255,0.3);
        }

        .profile-info h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .profile-role {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color:  #e31575;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #6c757d;
        }

        .info-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            color:  #e31575;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .btn-edit {
           background: #e31575;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px #ffb7ce;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .profile-container {
                margin-left: 0; /* Remove sidebar margin on mobile */
                margin-top: 60px; /* Adjust for mobile header */
                padding: 15px;
            }
            
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                padding: 30px 20px;
                text-align: center;
            }
            
            .profile-info h1 {
                font-size: 2rem;
            }
        }
    </style>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="profile-info">
                <h1>Administrator</h1>
                <div class="profile-role">System Administrator</div>
                <div class="profile-stats">
                    <div class="stat-card">
                        <span class="stat-number">150</span>
                        <div class="stat-label">Total Seniors</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">45</span>
                        <div class="stat-label">Pending Applications</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">12</span>
                        <div class="stat-label">Events This Month</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <!-- Personal Information -->
            <div class="profile-card">
                <h3 class="card-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h3>
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">System Administrator</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">admin@eldera.gov.ph</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">+63 912 345 6789</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Department</span>
                    <span class="info-value">Office of Senior Citizens Affairs</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Position</span>
                    <span class="info-value">System Administrator</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Employee ID</span>
                    <span class="info-value">OSCA-ADM-001</span>
                </div>
                <div style="margin-top: 20px;">
                    <a href="#" class="btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- System Information -->
            <div class="profile-card">
                <h3 class="card-title">
                    <i class="fas fa-cogs"></i>
                    System Information
                </h3>
                <div class="info-item">
                    <span class="info-label">Last Login</span>
                    <span class="info-value">{{ date('M d, Y - h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Created</span>
                    <span class="info-value">January 15, 2024</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Access Level</span>
                    <span class="info-value">Super Administrator</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value" style="color: #28a745;">Active</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Session Timeout</span>
                    <span class="info-value">2 hours</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Two-Factor Auth</span>
                    <span class="info-value" style="color: #28a745;">Enabled</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="profile-card" style="margin-top: 30px;">
            <h3 class="card-title">
                <i class="fas fa-history"></i>
                Recent Activity
            </h3>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Added new senior citizen record</div>
                    <div class="activity-time">2 hours ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Approved pension application</div>
                    <div class="activity-time">4 hours ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Created new event: Health Check-up</div>
                    <div class="activity-time">1 day ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Updated system settings</div>
                    <div class="activity-time">2 days ago</div>
                </div>
            </div>
        </div>
    </div>
</x-header>
</x-sidebar>