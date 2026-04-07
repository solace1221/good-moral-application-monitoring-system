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
        
        .two-tone-line {
            width: 100%;
            height: 10px;
            background: white;
            position: relative;
            margin-bottom: 5px;
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
        
        img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 60px;
        }
    </style>
</head>
<body>
    @php
        $footer = base64_encode(file_get_contents(public_path('images/footer.png')));
    @endphp
    <div class="two-tone-line"></div>
    <img src="data:image/png;base64,{{ $footer }}" alt="Footer" />
</body>
</html>
