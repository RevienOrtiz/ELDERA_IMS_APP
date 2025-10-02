<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .report-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .report-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .logo-container {
            margin-right: 20px;
        }
        
        .report-logo {
            max-width: 100px;
            height: auto;
        }
        
        .logo-fallback {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            padding: 10px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
        }
        
        .header-text h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        
        .header-text h3 {
            margin: 5px 0 0;
            color: #666;
            font-size: 16px;
        }
        
        .report-summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        
        .report-table-container {
            overflow-x: auto;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .report-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        
        .report-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .report-table tr:hover {
            background-color: #e9e9e9;
        }
        
        .report-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
            text-align: center;
        }
        
        @media print {
            body {
                background-color: white;
            }
            
            .report-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
            
            .print-controls {
                display: none;
            }
        }
        
        .print-controls {
            text-align: center;
            margin: 20px 0;
        }
        
        .print-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
    
    {!! $content !!}
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            // Uncomment the line below to automatically open the print dialog
            // window.print();
        };
    </script>
</body>
</html>