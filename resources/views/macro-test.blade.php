<!DOCTYPE html>
<html>
<head>
    <title>Laravel Collection Macros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background: #f9fafb;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 5px solid #4f46e5;
        }

        .key {
            font-weight: bold;
            color: #4f46e5;
        }

        .value {
            float: right;
            color: #111827;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1> Laravel Collection Macros Output</h1>

    @foreach($data as $key => $value)
        <div class="card">
            <span class="key">{{ $key }}</span>
            <span class="value">
                {{ is_bool($value) ? ($value ? 'true' : 'false') : $value }}
            </span>
            <div style="clear: both;"></div>
        </div>
    @endforeach

    <div class="footer">
        Powered by Spatie Collection Macros 
    </div>
</div>

</body>
</html>
