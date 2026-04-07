<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Moderator Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .header {
            background: #28a745;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .value {
            flex: 1;
        }
        .badge {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DEBUG: Moderator Profile</h1>
            <p>This is a minimal version of the profile page for debugging layout issues</p>
        </div>
        
        <div class="card">
            <h2>Profile Information</h2>
            
            <div class="row">
                <div class="label">Name:</div>
                <div class="value">{{ $user->fullname ?? $user->name ?? 'N/A' }}</div>
            </div>
            
            <div class="row">
                <div class="label">Email:</div>
                <div class="value">{{ $user->email ?? 'N/A' }}</div>
            </div>
            
            <div class="row">
                <div class="label">Account Type:</div>
                <div class="value">{{ $user->account_type ?? 'N/A' }}</div>
            </div>
            
            <div class="row">
                <div class="label">Role:</div>
                <div class="value"><span class="badge">SEC-OSA Moderator</span></div>
            </div>
            
            <div class="row">
                <div class="label">Created:</div>
                <div class="value">{{ $user->created_at ? $user->created_at->format('M d, Y H:i A') : 'N/A' }}</div>
            </div>
        </div>
        
        <div class="card">
            <h2>Debug Information</h2>
            
            <div class="row">
                <div class="label">User ID:</div>
                <div class="value">{{ $user->id ?? 'N/A' }}</div>
            </div>
            
            <div class="row">
                <div class="label">Student ID:</div>
                <div class="value">{{ $user->student_id ?? 'N/A' }}</div>
            </div>
            
            <div class="row">
                <div class="label">Available Attributes:</div>
                <div class="value">
                    <pre>{{ print_r($user->getAttributes(), true) }}</pre>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Navigation</h2>
            <ul>
                <li><a href="{{ route('sec_osa.dashboard') }}">Back to Dashboard</a></li>
                <li><a href="{{ route('sec_osa.profile') }}">Original Profile Page</a></li>
                <li><a href="{{ route('test.moderator.profile') }}">Test Profile Page</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</body>
</html>