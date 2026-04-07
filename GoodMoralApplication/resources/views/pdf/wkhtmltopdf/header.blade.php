<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            text-align: center;
        }
        
        img {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
            margin: 0 auto 5px auto;
        }
        
        h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        .two-tone-line {
            width: 100%;
            height: 10px;
            background: white;
            position: relative;
        }
        
        .two-tone-line::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background: #FFFF00;
        }
        
        .two-tone-line::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background: #00B050;
        }
    </style>
</head>
<body>
    @php
        $header = base64_encode(file_get_contents(public_path('images/header.png')));
    @endphp
    <img src="data:image/png;base64,{{ $header }}" alt="University Header" />
    <h3>OFFICE OF STUDENT AFFAIRS</h3>
    <div class="two-tone-line"></div>
</body>
</html>
