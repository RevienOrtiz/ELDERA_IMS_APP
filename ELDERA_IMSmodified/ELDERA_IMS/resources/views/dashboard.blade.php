<x-sidebar>
  <x-header>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ELDERA - Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5faff;
            font-size: clamp(14px, 2vw, 16px);
        }
        .main {
            margin-left: clamp(0px, 25vw, 250px);
            padding: clamp(50px, 8vh, 70px) 0 0 0;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            overflow-y: auto;
            padding: clamp(10px, 3vw, 20px);
            flex: 1;
        }
        
        .stats {
            display: flex;
            gap: clamp(10px, 2vw, 20px);
            margin-top: clamp(10px, 2vh, 20px);
            flex-wrap: wrap;
        }
        .stat {
            background: white;
            flex: 1;
            min-width: clamp(180px, 25vw, 250px);
            padding: clamp(12px, 2vw, 20px);
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .stat.green { background: #00CC63; color: white; }
        .stat.yellow { background: #F7B720; color: white; } 
        .stat.red { background: #F72020; color: white; }
        .stat.blue { background: #208FF7; color: white; }

        .stat-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .stat-left i {
            font-size: clamp(18px, 3vw, 24px);
            margin-bottom: 4px;
        }

        .stat-left span {
            font-size: clamp(12px, 1.5vw, 14px);
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-divider {
            width: clamp(3px, 0.5vw, 4px);
            height: clamp(50px, 8vw, 60px);
            background: linear-gradient(90deg, #252424 0%, #252424 50%, #edeaea 50%, #d3d3d3 100%);
            margin: 0 clamp(10px, 2vw, 15px);
            border-radius: 20px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-right {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .stat-right strong {
            font-size: clamp(20px, 4vw, 28px);
            font-weight: bold;
        }

        .stats {
            background: white;
            margin-top: 10px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .section {
            background: white;
            margin-top: clamp(15px, 3vh, 20px);
            padding: clamp(15px, 3vw, 20px);
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .section h3 {
            margin-top: 0;
            font-size: clamp(16px, 2.5vw, 20px);
        }
        .charts {
            display: flex;
            gap: clamp(15px, 3vw, 20px);
            flex-wrap: wrap;
        }
        .chart, .events {
            flex: 1;
            min-width: clamp(250px, 35vw, 280px);
            padding: clamp(8px, 1.5vw, 10px);
        }
       .events-panel {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px #0001;
        padding: clamp(12px, 2.5vw, 18px);
        min-width: clamp(280px, 40vw, 320px);
        flex: 1.2;
        display: flex;
        flex-direction: column;
    }
    .events-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    .events-table th, .events-table td {
        padding: clamp(4px, 1vw, 8px);
        text-align: left;
        font-size: clamp(12px, 1.5vw, 14px);
    }
    .events-table th {
        color: #222;
        font-weight: bold;
        border-bottom: 2px solid #e0e0e0;
    }
    .event-color {
        width: clamp(14px, 2vw, 18px);
        height: clamp(14px, 2vw, 18px);
        border-radius: 4px;
        display: inline-block;
        margin-right: clamp(6px, 1vw, 8px);
    }
    .event-general { background: #19e36c; }
    .event-health { background: #e33c3c; }
    .event-pension { background: #3c8be3; }
    .events-link {
        color: #3c8be3;
        font-weight: bold;
        text-align: right;
        text-decoration: none;
        margin-top: 4px;
        font-size: 14px;
        align-self: flex-end;
    }
        .btn {
            padding: clamp(8px, 1.5vw, 10px) clamp(15px, 3vw, 20px);
            margin: 5px;
            border: none;
            color: white;
            background: #277da1;
            cursor: pointer;
            border-radius: 5px;
            font-size: clamp(12px, 1.5vw, 14px);
    }
    

    .main-footer {
            background: #fff;
            padding: clamp(15px, 3vw, 20px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -1px 4px #0001;
            position: fixed;
            bottom: 0;
            left: clamp(0px, 25vw, 250px);
            right: 0;
            height: clamp(15px, 3vh, 20px);
            z-index: 1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .footer-logo {
            height: clamp(40px, 6vh, 50px);
            display: block;
            margin: 0 auto;
        }

        /* Pie Charts */
        .chart-container {
          width: clamp(80px, 15vw, 120px);
          height: clamp(80px, 15vw, 120px);
          margin: 0 auto;
          max-width: 100%;
        }
        .card {
          background: #fff;
          border-radius: 10px;
          box-shadow: 0 2px 8px #0001;
          padding: 0;
          margin-bottom: clamp(10px, 2vh, 15px);
          display: flex;
          flex-direction: column;
          min-width: clamp(220px, 30vw, 260px);
          max-width: 100%;
        }
        .card-header {
          background: #555555;
          color: #fff;
          font-weight: bold;
          text-transform: uppercase;
          font-size: clamp(11px, 1.5vw, 13px);
          padding: clamp(6px, 1.5vw, 8px) clamp(12px, 2.5vw, 16px);
          border-radius: 10px 10px 0 0;
          letter-spacing: 1px;
        }
        .card-content {
          padding: clamp(8px, 2vw, 12px) clamp(12px, 2.5vw, 16px) clamp(4px, 1vw, 6px) clamp(12px, 2.5vw, 16px);
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .card-footer {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          padding: 0 clamp(12px, 2.5vw, 16px) clamp(8px, 2vh, 12px) clamp(12px, 2.5vw, 16px);
        }
        .legend {
          display: flex;
          gap: clamp(8px, 2vw, 12px);
          margin-top: clamp(6px, 1.5vh, 8px);
          font-size: clamp(10px, 1.5vw, 12px);
          justify-content: center;
          flex-wrap: wrap;
        }
        .legend-item {
          display: flex;
          align-items: center;
          gap: clamp(3px, 0.5vw, 4px);
        }
        .legend-color {
          width: clamp(12px, 2vw, 14px);
          height: clamp(12px, 2vw, 14px);
          border-radius: 3px;
          display: inline-block;
        }
        .legend-male { background: #208FF7; }
        .legend-female { background: #F72020; }
        .legend-pension { background: #e31575 }
        .legend-nopension { background: #e0e0e0; }

        .event-general { background: #19e36c; }
        .event-pension { background: #3c8be3; }
        .event-health { background: #e33c3c }
        .event-id_claiming { background: #ffd500; }

        .events-card {
          background: #fff;
          border-radius: 10px;
          box-shadow: 0 2px 8px #0001;
          padding: 0;
          display: flex;
          flex-direction: column;
          min-width: 320px;
          max-width: 100%;
          border: 1px solid #e0e0e0;
          overflow: hidden;
        }
        .events-header {
          background: #555555;
          color: #fff;
          font-weight: bold;
          text-transform: uppercase;
          font-size: 13px;
          padding: 8px 16px;
          border-radius: 5px 5px 0 0;
          letter-spacing: 1px;
        }
        .events-table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 0;
          background: white;
        }
        .events-table th, .events-table td {
          padding: 8px 12px;
          text-align: left;
          font-size: 14px;
          border-bottom: 1px solid #e0e0e0;
        }
        .events-table th {
          background: #f8f9fa;
          color: #333;
          font-weight: bold;
          font-size: 13px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }
        .events-table-header {
          background: #f8f9fa;
          border-bottom: 2px solid #dee2e6;
        }
        .events-table-header th {
          background: #f8f9fa;
          position: static;
          z-index: auto;
        }
        .events-table tbody tr:hover {
          background: #f8f9fa;
        }
        .events-table tbody tr:last-child td {
          border-bottom: none;
        }
        .event-color {
          width: 12px;
          height: 12px;
          border-radius: 3px;
          display: inline-block;
        }
        .event-general { background: #19e36c; }
        .event-health { background: #e33c3c; }
        .event-pension { background: #3c8be3; }
        .event-id_claiming { background: #ffd500; }
        .events-link {
          color: #277da1;
          font-weight: bold;
          text-align: right;
          text-decoration: none;
          margin-top: 4px;
          font-size: 14px;
          align-self: flex-end;
        }

        /* Mini Calendar Styles */
        .mini-calendar {
          width: 100%;
          max-width: 280px;
          margin: 0 auto;
          font-size: 12px;
          height: 100%;
          max-height: 250px;
          display: flex;
          flex-direction: column;
          border: 1px solid #e0e0e0;
          border-radius: 8px;
          overflow: hidden;
        }
        
        .calendar-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 0;
          padding: 12px 15px;
          background: #f8f9fa;
          border-bottom: 1px solid #e0e0e0;
        }
        
        .calendar-nav {
          background: #277da1;
          color: white;
          border: none;
          border-radius: 4px;
          width: 28px;
          height: 28px;
          cursor: pointer;
          font-size: 14px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: bold;
        }
        
        .calendar-nav:hover {
          background: #1e6b8c;
        }
        
        .calendar-month-year {
          font-weight: bold;
          font-size: 16px;
          color: #333;
        }
        
        .calendar-weekdays {
          display: grid;
          grid-template-columns: repeat(7, 1fr);
          gap: 0;
          margin-bottom: 0;
          background: #f1f3f4;
          border-bottom: 1px solid #e0e0e0;
        }
        
        .weekday {
          text-align: center;
          font-weight: bold;
          font-size: 12px;
          color: #666;
          padding: 8px 4px;
          border-right: 1px solid #e0e0e0;
        }
        
        .weekday:last-child {
          border-right: none;
        }
        
        .calendar-days {
          display: grid;
          grid-template-columns: repeat(7, 1fr);
          gap: 0;
          flex: 1;
          background: white;
        }
        
        .calendar-day {
          aspect-ratio: 1;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 12px;
          cursor: pointer;
          position: relative;
          background: white;
          color: #333;
          min-height: 32px;
          border-right: 1px solid #e0e0e0;
          border-bottom: 1px solid #e0e0e0;
          transition: background-color 0.2s ease;
        }
        
        .calendar-day:nth-child(7n) {
          border-right: none;
        }
        
        .calendar-day:hover {
          background: #f5f5f5;
        }
        
        .calendar-day.today {
          background: #e3f2fd;
          color: #1976d2;
          font-weight: bold;
          border: 2px solid #1976d2;
        }
        
        .calendar-day.other-month {
          color: #bbb;
          background: #fafafa;
        }
        
        .calendar-day.has-event {
          font-weight: bold;
        }
        
        .calendar-day.has-event.today {
          border: 2px solid #1976d2;
        }
        
        /* Event type specific colors */
        .calendar-day.event-general {
          background: #e8f5e8 !important;
          color: #2e7d32 !important;
        }
        
        .calendar-day.event-health {
          background: #ffebee !important;
          color: #c62828 !important;
        }
        
        .calendar-day.event-pension {
          background: #e3f2fd !important;
          color: #1565c0 !important;
        }
        
        .calendar-day.event-id {
          background: #fffde7 !important;
          color: #f57f17 !important;
        }
        
        .event-indicator {
          position: absolute;
          bottom: 2px;
          right: 2px;
          width: 6px;
          height: 6px;
          border-radius: 50%;
          border: 1px solid white;
          box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .charts {
          display: flex;
          gap: 20px;
        }

        @media (max-width: 900px) {
          .charts {
            flex-direction: column;
          }
        }

        /* Filter Dropdown Styling */
        .filter-group {
            position: relative;
        }

        .filter-btn {
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 6px;
            padding: clamp(6px, 1.5vw, 8px) clamp(10px, 2vw, 12px);
            font-size: clamp(12px, 1.5vw, 14px);
            font-weight: 500;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: clamp(4px, 1vw, 6px);
            transition: all 0.3s ease;
            white-space: nowrap;
            min-width: clamp(150px, 25vw, 200px);
        }

        .filter-btn:hover {
            background: #e9ecef;
            border-color: #CC0052;
            color: #CC0052;
        }

        .filter-btn.active {
            background: #CC0052;
            border-color: #CC0052;
            color: #fff;
        }

        .filter-btn i.fas.fa-chevron-down {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .filter-btn.active i.fas.fa-chevron-down {
            transform: rotate(180deg);
        }

        .filter-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
            min-width: clamp(150px, 25vw, 200px);
            margin-top: clamp(3px, 1vh, 5px);
        }

        .filter-dropdown.show {
            display: block;
        }

        .filter-dropdown-content {
            padding: clamp(8px, 2vw, 10px);
            max-height: clamp(200px, 40vh, 300px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: clamp(6px, 1.5vw, 8px);
            padding: clamp(4px, 1vw, 5px);
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            font-size: clamp(12px, 1.5vw, 14px);
        }

        .filter-option:hover {
            background: #f8f9fa;
        }

        .filter-option input[type="radio"] {
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main {
                margin-left: 200px;
            }
            
            .header {
                left: 200px;
            }
            
            .main-footer {
                left: 200px;
            }
        }

        @media (max-width: 992px) {
            .main {
                margin-left: 0;
                padding-top: 60px;
            }
            
            .header {
                left: 0;
            }
            
            .main-footer {
                left: 0;
            }
            
            .stats {
                gap: 15px;
            }
            
            .stat {
                min-width: 180px;
                padding: 15px;
            }
            
            .charts {
                gap: 15px;
            }
            
            .card {
                min-width: 240px;
            }
            
            .events-card {
                min-width: 280px;
            }
        }

        @media (max-width: 768px) {
            .main {
                margin-left: 0;
                padding-top: 60px;
            }
            
            .header {
                left: 0;
            }
            
            .main-footer {
                left: 0;
            }
            
            .stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .stat {
                min-width: unset;
                width: 100%;
            }
            
            .charts {
                flex-direction: column;
                gap: 15px;
            }
            
            .card, .events-card {
                min-width: unset;
                width: 100%;
            }
            
            .chart-container {
                width: 100px;
                height: 100px;
            }
            
            .events-table th, .events-table td {
                padding: 6px 4px;
                font-size: 12px;
            }
            
            .legend {
                flex-wrap: wrap;
                gap: 8px;
            }
        }

        @media (max-width: 576px) {
            .main {
                margin-left: 0;
                padding-top: 60px;
            }
            
            .header {
                left: 0;
            }
            
            .main-footer {
                left: 0;
            }
            
            .main-content {
                padding: 10px;
            }
            
            .stat {
                padding: 12px;
                flex-direction: column;
                text-align: center;
            }
            
            .stat-divider {
                width: 60px;
                height: 4px;
                margin: 10px 0;
            }
            
            .stat-left span {
                font-size: 12px;
            }
            
            .stat-right strong {
                font-size: 24px;
            }
            
            .chart-container {
                width: 80px;
                height: 80px;
            }
            
            .events-table {
                font-size: 11px;
            }
            
            .events-table th, .events-table td {
                padding: 4px 2px;
            }
            
            .card-header, .events-header {
                font-size: 11px;
                padding: 6px 12px;
            }
        }

        /* Extra small screens - for very narrow windows */
        @media (max-width: 400px) {
            .main {
                margin-left: 0;
                padding-top: 50px;
            }
            
            .main-content {
                padding: 5px;
            }
            
            .stat {
                padding: 8px;
                min-height: auto;
            }
            
            .stat-left i {
                font-size: 18px;
            }
            
            .stat-left span {
                font-size: 10px;
            }
            
            .stat-right strong {
                font-size: 20px;
            }
            
            .chart-container {
                width: 60px;
                height: 60px;
            }
            
            .card-header, .events-header {
                font-size: 10px;
                padding: 4px 8px;
            }
            
            .events-table th, .events-table td {
                padding: 2px 1px;
                font-size: 9px;
            }
            
            .legend {
                font-size: 10px;
                gap: 4px;
            }
            
            .legend-color {
                width: 10px;
                height: 10px;
            }
            
            .filter-btn {
                min-width: 150px;
                font-size: 12px;
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>
    
   
    
    <div class="main">
        <div class="main-content">
                 <div class="stats">
            <div class="stat green">
                <div class="stat-left">
                    <i class="fas fa-home"></i>
                    <span>BARANGAY</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-right">
                    <strong>{{ $stats['barangays']['total'] ?? 0 }}</strong>
                </div>
            </div>

            <div class="stat yellow">
                <div class="stat-left">
                    <i class="fas fa-users"></i>
                    <span>SENIORS</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-right">
                    <strong>{{ number_format($stats['seniors']['total'] ?? 0) }}</strong>
                </div>
            </div>

            <div class="stat red">
                <div class="stat-left">
                    <i class="fas fa-female"></i>
                    <span>FEMALE</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-right">
                    <strong>{{ number_format($stats['seniors']['female'] ?? 0) }}</strong>
                </div>
            </div>
            <div class="stat blue">
                <div class="stat-left">
                    <i class="fas fa-male"></i>
                    <span>MALE</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-right">
                    <strong>{{ number_format($stats['seniors']['male'] ?? 0) }}</strong>
                </div>
            </div>
        </div>

      {{-- Dropdown Button --}}
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div class="filter-group">
                    <button class="filter-btn" id="barangay-btn" onclick="toggleBarangayDropdown()">
                        <span id="barangay-text">ALL BARANGAY</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-dropdown" id="barangay-dropdown">
                        <div class="filter-dropdown-content">
                            <div class="filter-options">
                                <label class="filter-option" onclick="selectBarangay('')">
                                    <input type="radio" name="barangay" value="" checked>
                                    <span>All Barangay</span>
                                </label>
                                @foreach($stats['barangays']['barangays'] ?? [] as $barangay)
                                <label class="filter-option" onclick="selectBarangay('{{ $barangay['name'] }}')">
                                    <input type="radio" name="barangay" value="{{ $barangay['name'] }}">
                                    <span>{{ $barangay['name'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                {{-- Three Pie Charts in a Row --}}
                <div class="charts" style="width: 100%; margin-bottom: 10px;">
                    <div class="card" style="flex:1;">
                        <div class="card-header">TOTAL # OF SENIOR CITIZEN</div>
                        <div class="card-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 200px; height: 120px; margin: 30px auto 30px auto; max-width: 100%; display: flex; align-items: center; justify-content: center;">
                                <canvas id="genderPieChart" width="120" height="120"></canvas>
                            </div>
                            <div class="legend">
                                <div class="legend-item"><span class="legend-color legend-male"></span>MALE</div>
                                <div class="legend-item"><span class="legend-color legend-female"></span>FEMALE</div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="flex:1;">
                        <div class="card-header">With Pension</div>
                        <div class="card-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 200px; height: 120px; margin: 30px auto 30px auto; max-width: 100%; display: flex; align-items: center; justify-content: center;">
                                <canvas id="pensionPieChart" width="120" height="120"></canvas>
                            </div>
                            <div class="legend">
                                <div class="legend-item"><span class="legend-color legend-pension"></span>WITH PENSION</div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="flex:1;">
                        <div class="card-header">TOTAL EVENTS</div>
                        <div class="card-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 200px; height: 120px; margin: 30px auto 30px auto; max-width: 100%; display: flex; align-items: center; justify-content: center;">
                                <canvas id="eventsPieChart" width="120" height="120"></canvas>
                            </div>
                            <div class="legend" style="flex-wrap: wrap;">
                                <div class="legend-item"><span class="legend-color event-general"></span>GENERAL</div>
                                <div class="legend-item"><span class="legend-color event-pension"></span>PENSION</div>
                                <div class="legend-item"><span class="legend-color event-health"></span>HEALTH</div>
                                <div class="legend-item"><span class="legend-color event-id_claiming"></span>ID CLAIMING</div>
                            </div>
                        </div>
                    </div>

                     <div class="card" style="flex:1;">
                        <div class="card-header">CALENDAR</div>
                        <div class="card-content">
                            <div class="mini-calendar" id="miniCalendar">
                                <div class="calendar-header">
                                    <button class="calendar-nav" id="prevMonth">&lt;</button>
                                    <span class="calendar-month-year" id="monthYear"></span>
                                    <button class="calendar-nav" id="nextMonth">&gt;</button>
                                </div>
                                <div class="calendar-weekdays">
                                    <div class="weekday">S</div>
                                    <div class="weekday">M</div>
                                    <div class="weekday">T</div>
                                    <div class="weekday">W</div>
                                    <div class="weekday">T</div>
                                    <div class="weekday">F</div>
                                    <div class="weekday">S</div>
                                </div>
                                <div class="calendar-days" id="calendarDays">
                                    <!-- Days will be generated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Age Bar Chart and Events Table Side by Side --}}
                <div style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%;">
                    {{-- Age Bar Chart --}}
                    <div class="card" style="flex: 1; min-width: 280px;">
                        <div class="card-header">Senior Citizen by Age</div>
                        <div class="card-content">
                            <div style="width:100%; height:200px; display:flex; align-items:center; justify-content:center;">
                                <canvas id="ageBarChart" width="400" height="180"></canvas>
                            </div>
                            <div class="legend">
                                <div class="legend-item"><span class="legend-color legend-male"></span>MALE</div>
                                <div class="legend-item"><span class="legend-color legend-female"></span>FEMALE</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Events Card --}}
                    <div class="card" style="flex:1; min-width: 280px; height: 300px; display: flex; flex-direction: column;">
                        <div class="card-header">EVENTS</div>
                        <div class="events-table-container" style="flex: 1; overflow: hidden; display: flex; flex-direction: column;">
                            <div class="events-table-header">
                                <table class="events-table" style="margin-bottom: 0; table-layout: fixed;">
                                    <colgroup>
                                        <col style="width: 20px;">
                                        <col style="width: 40%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>EVENTS</th>
                                            <th>DATE</th>
                                            <th>TIME</th>
                                            <th>PLACE</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="events-table-body" style="flex: 1; overflow-y: auto;">
                                <table class="events-table" style="table-layout: fixed;">
                                    <colgroup>
                                        <col style="width: 20px;">
                                        <col style="width: 40%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                    </colgroup>
                                    <tbody>
                                        @forelse($events as $event)
                                        <tr>
                                            <td style="width: 20px; text-align: center; vertical-align: middle; padding: 8px 4px;"><span class="event-color event-{{ $event->event_type }}"></span></td>
                                            <td><a href="{{ route('events.show', $event->id) }}" style="color:#277da1; text-decoration:none;">{{ $event->title }}</a></td>
                                            <td>{{ $event->event_date->format('d/m/y') }}</td>
                                            <td>{{ $event->start_time->format('g:i A') }}</td>
                                            <td>{{ $event->location }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 20px; color: #666;">No upcoming events</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    {{-- <footer class="main-footer">
        <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas" class="footer-logo">
    </footer> --}}

   
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded');
    return;
  }

  // Gender Pie Chart
  const genderCanvas = document.getElementById('genderPieChart');
  if (genderCanvas) {
    const genderCtx = genderCanvas.getContext('2d');
    const genderData = [{{ $stats['seniors']['male'] ?? 0 }}, {{ $stats['seniors']['female'] ?? 0 }}]; // Male, Female
    const genderTotal = genderData.reduce((a, b) => a + b, 0);
    
    window.genderPieChart = new Chart(genderCtx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: genderData,
          backgroundColor: ['#208FF7', '#F72020'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: false 
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const labels = ['Male', 'Female'];
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = ((context.parsed / total) * 100).toFixed(1);
                return `${labels[context.dataIndex]}: ${context.parsed.toLocaleString()} (${percentage}%)`;
               
              }
            }
          }
        }
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function(chart) {
          const width = chart.width;
          const height = chart.height;
          const ctx = chart.ctx;
          
          ctx.restore();
          ctx.font = 'bold 16px Arial';
          ctx.fillStyle = '#333';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          
          const centerX = width / 2;
          const centerY = height / 2;
          
          // Calculate total from current data
          const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
          ctx.fillText(total.toLocaleString(), centerX, centerY);
          
          ctx.save();
        }
      }]
    });
  }

  // Pension Pie Chart
  const pensionCanvas = document.getElementById('pensionPieChart');
  if (pensionCanvas) {
    const pensionCtx = pensionCanvas.getContext('2d');
    const pensionData = [{{ $stats['seniors']['with_pension'] ?? 0 }}, {{ $stats['seniors']['without_pension'] ?? 0 }}]; // With Pension, Without Pension
    const pensionWithCount = {{ $stats['seniors']['with_pension'] ?? 0 }}; // Number of seniors with pension

    window.pensionPieChart = new Chart(pensionCtx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: pensionData,
          backgroundColor: ['#e31575', '#e0e0e0'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: false 
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const labels = ['With Pension', 'Without Pension'];
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                return `${labels[context.dataIndex]}: ${context.parsed.toLocaleString()} (${percentage}%)`;
              }
            }
          }
        }
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function(chart) {
          const width = chart.width;
          const height = chart.height;
          const ctx = chart.ctx;
          
          ctx.restore();
          ctx.font = 'bold 16px Arial';
          ctx.fillStyle = '#333';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          
          const centerX = width / 2;
          const centerY = height / 2;
          
          // Display number of seniors with pension (first value in the data array)
          const withPensionCount = chart.data.datasets[0].data[0];
          ctx.fillText(withPensionCount.toLocaleString(), centerX, centerY);
          
          ctx.save();
        }
      }]
    });
  }

  // Events Pie Chart
  const eventsCanvas = document.getElementById('eventsPieChart');
  if (eventsCanvas) {
    const eventsCtx = eventsCanvas.getContext('2d');
    const eventsData = [
      {{ $eventsByType['general'] ?? 0 }}, 
      {{ $eventsByType['pension'] ?? 0 }}, 
      {{ $eventsByType['health'] ?? 0 }}, 
      {{ $eventsByType['id_claiming'] ?? 0 }}
    ];
    const totalEvents = eventsData.reduce((a, b) => a + b, 0);

    window.eventsPieChart = new Chart(eventsCtx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: eventsData,
          backgroundColor: ['#19e36c', '#3c8be3', '#e33c3c', '#ffd500'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: false 
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const labels = ['General', 'Pension', 'Health', 'ID Claiming'];
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                return `${labels[context.dataIndex]}: ${context.parsed.toLocaleString()} (${percentage}%)`;
              }
            }
          }
        }
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function(chart) {
          const width = chart.width;
          const height = chart.height;
          const ctx = chart.ctx;
          
          ctx.restore();
          ctx.font = 'bold 16px Arial';
          ctx.fillStyle = '#333';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          
          const centerX = width / 2;
          const centerY = height / 2;
          
          // Display total events count
          const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
          ctx.fillText(total.toLocaleString(), centerX, centerY);
          
          ctx.save();
        }
      }]
    });
  }

  // Age Bar Chart
  const ageBarCanvas = document.getElementById('ageBarChart');
  if (ageBarCanvas) {
    const ageBarCtx = ageBarCanvas.getContext('2d');
    const ageDistribution = @json($stats['age_distribution'] ?? []);
    
    window.ageBarChart = new Chart(ageBarCtx, {
      type: 'bar',
      data: {
        labels: ['60-65', '66-70', '71-75', '76-80', '81-85', '86-90', '90+'],
        datasets: [
          {
            label: 'Male',
            data: [
              ageDistribution['60-65']?.male || 0,
              ageDistribution['66-70']?.male || 0,
              ageDistribution['71-75']?.male || 0,
              ageDistribution['76-80']?.male || 0,
              ageDistribution['81-85']?.male || 0,
              ageDistribution['86-90']?.male || 0,
              ageDistribution['90+']?.male || 0
            ],
            backgroundColor: '#208FF7',
            borderRadius: 6,
            barPercentage: 0.5,
            categoryPercentage: 0.6
          },
          {
            label: 'Female',
            data: [
              ageDistribution['60-65']?.female || 0,
              ageDistribution['66-70']?.female || 0,
              ageDistribution['71-75']?.female || 0,
              ageDistribution['76-80']?.female || 0,
              ageDistribution['81-85']?.female || 0,
              ageDistribution['86-90']?.female || 0,
              ageDistribution['90+']?.female || 0
            ],
            backgroundColor: '#F72020',
            borderRadius: 6,
            barPercentage: 0.5,
            categoryPercentage: 0.6
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              font: { size: 12 },
              color: '#444'
            }
          },
          y: {
            beginAtZero: true,
            grid: { color: '#8882', borderDash: [4,4] },
            ticks: {
              font: { size: 12 },
              color: '#444',
              stepSize: 20
            }
          }
        }
      }
    });
  }

  // Mini Calendar
  initializeMiniCalendar();
});

// Mini Calendar Functions
function initializeMiniCalendar() {
  const eventsData = @json($events ?? []);
  let currentDate = new Date();
  
  function renderCalendar(date) {
    const monthYear = document.getElementById('monthYear');
    const calendarDays = document.getElementById('calendarDays');
    
    if (!monthYear || !calendarDays) return;
    
    const year = date.getFullYear();
    const month = date.getMonth();
    
    // Set month/year header
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    monthYear.textContent = `${monthNames[month]} ${year}`;
    
    // Clear previous days
    calendarDays.innerHTML = '';
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    // Add empty cells for days before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
      const emptyDay = document.createElement('div');
      emptyDay.className = 'calendar-day other-month';
      const prevMonthDay = new Date(year, month, 0 - (startingDayOfWeek - 1 - i));
      emptyDay.textContent = prevMonthDay.getDate();
      calendarDays.appendChild(emptyDay);
    }
    
    // Add days of current month
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
      const dayElement = document.createElement('div');
      dayElement.className = 'calendar-day';
      dayElement.textContent = day;
      
      // Check if it's today
      if (year === today.getFullYear() && 
          month === today.getMonth() && 
          day === today.getDate()) {
        dayElement.classList.add('today');
      }
      
      // Check if there are events on this day
      const dayDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const dayEvents = eventsData.filter(event => {
        const eventDate = new Date(event.event_date).toISOString().split('T')[0];
        return eventDate === dayDate;
      });
      
      if (dayEvents.length > 0) {
        dayElement.classList.add('has-event');
        
        // Get the primary event type for CSS class
        const primaryEventType = dayEvents[0].event_type;
        
        // Add specific event type class for styling
        switch(primaryEventType) {
          case 'general':
            dayElement.classList.add('event-general');
            break;
          case 'health':
            dayElement.classList.add('event-health');
            break;
          case 'pension':
            dayElement.classList.add('event-pension');
            break;
          case 'id_claiming':
            dayElement.classList.add('event-id');
            break;
          default:
            dayElement.classList.add('event-general');
        }
        
        // Create small indicators for multiple event types
        if (dayEvents.length > 1) {
          const eventTypes = [...new Set(dayEvents.map(event => event.event_type))];
          eventTypes.slice(1).forEach((eventType, index) => {
            const indicator = document.createElement('div');
            indicator.className = 'event-indicator';
            
            // Set color based on event type
            let color = '#4caf50'; // default green
            switch(eventType) {
              case 'general':
                color = '#4caf50'; // green
                break;
              case 'health':
                color = '#f44336'; // red
                break;
              case 'pension':
                color = '#2196f3'; // blue
                break;
              case 'id_claiming':
                color = '#ffc107'; // yellow
                break;
              default:
                color = '#4caf50'; // default green
            }
            
            indicator.style.backgroundColor = color;
            indicator.style.right = `${2 + (index * 8)}px`;
            
            dayElement.appendChild(indicator);
          });
        }
      }
      
      calendarDays.appendChild(dayElement);
    }
    
    // Add remaining cells to complete the grid
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells; // 6 rows × 7 days
    for (let i = 1; i <= remainingCells && i <= 14; i++) {
      const nextMonthDay = document.createElement('div');
      nextMonthDay.className = 'calendar-day other-month';
      nextMonthDay.textContent = i;
      calendarDays.appendChild(nextMonthDay);
    }
  }
  
  // Navigation event listeners
  document.getElementById('prevMonth')?.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  });
  
  document.getElementById('nextMonth')?.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });
  
  // Initial render
  renderCalendar(currentDate);
}

// Barangay filtering function
function filterByBarangay(selectedBarangay = null) {
    if (selectedBarangay === null) {
        selectedBarangay = document.getElementById('barangay')?.value || '';
    }
    
    if (selectedBarangay && selectedBarangay !== 'ALL BARANGAY' && selectedBarangay !== '') {
        // Show loading state
        showLoadingState();
        
        // Fetch barangay-specific stats
        fetch(`/api/barangay-stats/${encodeURIComponent(selectedBarangay)}?t=${Date.now()}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                console.log('Seniors total:', data.seniors?.total);
                console.log('Seniors male:', data.seniors?.male);
                console.log('Seniors female:', data.seniors?.female);
                updateDashboardStats(data);
                hideLoadingState();
            })
            .catch(error => {
                console.error('Detailed error:', error);
                console.error('Error message:', error.message);
                hideLoadingState();
                alert(`Error loading barangay statistics: ${error.message}. Please check the console for details.`);
            });
    } else {
        // Reset to all barangays
        location.reload();
    }
}

function showLoadingState() {
    // Add loading overlay or spinner
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-overlay';
    loadingDiv.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    loadingDiv.innerHTML = '<div style="color: white; font-size: 18px;">Loading statistics...</div>';
    document.body.appendChild(loadingDiv);
}

function hideLoadingState() {
    const loadingDiv = document.getElementById('loading-overlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// New dropdown functions
function toggleBarangayDropdown() {
    const dropdown = document.getElementById('barangay-dropdown');
    const btn = document.getElementById('barangay-btn');
    
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        btn.classList.remove('active');
    } else {
        // Close any other open dropdowns
        document.querySelectorAll('.filter-dropdown.show').forEach(d => d.classList.remove('show'));
        document.querySelectorAll('.filter-btn.active').forEach(b => b.classList.remove('active'));
        
        dropdown.classList.add('show');
        btn.classList.add('active');
    }
}

function selectBarangay(barangayName) {
    const btn = document.getElementById('barangay-btn');
    const text = document.getElementById('barangay-text');
    const dropdown = document.getElementById('barangay-dropdown');
    
    // Update button text
    text.textContent = barangayName || 'ALL BARANGAY';
    
    // Close dropdown
    dropdown.classList.remove('show');
    btn.classList.remove('active');
    
    // Update radio button selection
    document.querySelectorAll('input[name="barangay"]').forEach(radio => {
        radio.checked = radio.value === barangayName;
    });
    
    // Call the existing filter function
    filterByBarangay(barangayName);
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.filter-group')) {
        document.querySelectorAll('.filter-dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
        document.querySelectorAll('.filter-btn.active').forEach(btn => {
            btn.classList.remove('active');
        });
    }
});

function updateDashboardStats(data) {
    console.log('Updating dashboard with data:', data);
    
    // Update stats cards
    const statCards = document.querySelectorAll('.stat-right strong');
    console.log('Found stat cards:', statCards.length);
    
    if (statCards.length >= 4) {
        statCards[0].textContent = data.barangays?.selected || 'N/A';
        statCards[1].textContent = (data.seniors?.total || 0).toLocaleString();
        statCards[2].textContent = (data.seniors?.female || 0).toLocaleString();
        statCards[3].textContent = (data.seniors?.male || 0).toLocaleString();
        console.log('Stats cards updated successfully');
    } else {
        console.error('Not enough stat cards found. Expected 4, found:', statCards.length);
    }
    
    // Update gender pie chart
    if (window.genderPieChart) {
        const newGenderData = [parseInt(data.seniors?.male) || 0, parseInt(data.seniors?.female) || 0];
        const newGenderTotal = newGenderData.reduce((a, b) => a + b, 0);
        
        console.log('Updating gender chart with data:', newGenderData);
        console.log('Gender chart total:', newGenderTotal);
        console.log('Current chart data before update:', window.genderPieChart.data.datasets[0].data);
        
        window.genderPieChart.data.datasets[0].data = newGenderData;
        window.genderPieChart.update();
        
        console.log('Current chart data after update:', window.genderPieChart.data.datasets[0].data);
        console.log('Gender pie chart updated with total:', newGenderTotal);
    } else {
        console.warn('Gender pie chart not found');
    }
    
    // Update pension pie chart
    if (window.pensionPieChart) {
        window.pensionPieChart.data.datasets[0].data = [parseInt(data.seniors?.with_pension) || 0, parseInt(data.seniors?.without_pension) || 0];
        window.pensionPieChart.update();
        console.log('Pension pie chart updated');
    } else {
        console.warn('Pension pie chart not found');
    }
    
    // Update age distribution bar chart
    if (window.ageBarChart) {
        const ageDistribution = data.age_distribution || {};
        window.ageBarChart.data.datasets[0].data = [
            ageDistribution['60-65']?.male || 0,
            ageDistribution['66-70']?.male || 0,
            ageDistribution['71-75']?.male || 0,
            ageDistribution['76-80']?.male || 0,
            ageDistribution['81-85']?.male || 0,
            ageDistribution['86-90']?.male || 0,
            ageDistribution['90+']?.male || 0
        ];
        window.ageBarChart.data.datasets[1].data = [
            ageDistribution['60-65']?.female || 0,
            ageDistribution['66-70']?.female || 0,
            ageDistribution['71-75']?.female || 0,
            ageDistribution['76-80']?.female || 0,
            ageDistribution['81-85']?.female || 0,
            ageDistribution['86-90']?.female || 0,
            ageDistribution['90+']?.female || 0
        ];
        window.ageBarChart.update();
        console.log('Age bar chart updated');
    } else {
        console.warn('Age bar chart not found');
    }
}

</script>

</body>
</html>
    </x-head>
</x-sidebar>