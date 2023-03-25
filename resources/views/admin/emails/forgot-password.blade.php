<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    <style>
        .container{
            width:92%;
            font-size: 105%;
            margin: auto;
            color: #222;
            font-family: Calibri, Roboto, Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        h1, h2, h3, h4, h5, h6{
            margin-top: 0;
            margin-bottom: 5px;
        }
        h5, h6{
            font-size: 100%;
        }
        .text-center{
            text-align: center;
        }
        .text-success{
            color: #1977e3!important;
        }
        .card{
            border: 1px solid #aaa;
            box-shadow: 0 0 10px #ccc;
            border-radius: 5px;
        }
        .card .card-header{
            border-bottom: 1px solid #e4e4e4;
            padding: 10px;
            background: #fafafa;
        }
        .card .card-body{
            padding: 10px;
        }
        .btn{
            cursor:pointer;
            border: 1px solid #aaa;
            padding: 5px;
            text-decoration: none;
            border-radius: 3px;
            display: inline-block;
        }
        .btn-primary{
            background-color: #e4e4e4;
            color: #1977e3!important;
            border-color: #bfbfbf;
            transition: .3s;
        }
        .btn-primary:hover{
            background-color: #c7c7c7;
        }
        .d-block{
            margin:5px 0;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="display-4 text-success text-center">{{ env("APP_NAME") }}</h1>
            </div>
            <div class="card-body">
                <h3>Reset Password Request</h3>
                <h5>Hi! {{ $name }},</h5>
                <p>
                    You submitted forgot password form on our website, so please click
                    Reset Password button shown below to proceed further.
                </p>
                <p>
                    (In case you did not requested for reset password, please ignore this message.)
                </p>
                <a href="{{ $link }}" target="blank" class="btn btn-primary mb-2">Reset Password</a>
                <span class="d-block text-muted">*Above link will be expired in 4 hours after generation.</span>
                <br>
                <h6>Kind regards!</h6>
                <h6>{{ env("APP_NAME") }}</h6>
            </div>
        </div>
    </div>
</body>
</html>
