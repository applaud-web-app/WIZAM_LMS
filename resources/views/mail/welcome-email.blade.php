<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to [Your Company Name]!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f8f8;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{$data['student']}}!</h1>
        
        {{$data['content']}}
        <p>We are thrilled to have you with us at [Your Company Name]. Thank you for signing up! We are excited to help you [brief description of what your service does].</p>
        
        <p>If you have any questions or need help getting started, feel free to reach out to us at any time. We're here to assist you!</p>

        <a href="https://www.wizam.com/" class="button">Get Started</a>

        <p>We look forward to helping you achieve [desired outcome from using your service]!</p>

        <div class="footer">
            <p>Thank you for choosing [Your Company Name].</p>
            <p>If you didn't sign up for this account, please disregard this email.</p>
        </div>
    </div>
</body>
</html>
