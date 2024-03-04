<!DOCTYPE html>
<html lang="fa">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ورود | MegaShop</title>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
        <style media="screen">
            *,
            *:before,
            *:after {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }

            body {
                background-color: #080710;
            }

            .background {
                width: 430px;
                height: 520px;
                position: absolute;
                transform: translate(-50%, -50%);
                left: 50%;
                top: 50%;
            }

            .background .shape {
                height: 180px;
                width: 180px;
                position: absolute;
                border-radius: 50%;
            }

            .shape:first-child {
                background: linear-gradient( #1845ad, #23a2f6);
                left: -80px;
                top: -80px;
            }

            .shape:last-child {
                background: linear-gradient( to right, #ff512f, #f01919);
                right: -30px;
                bottom: -80px;
            }

            form {
                height: auto;
                width: 400px;
                background-color: rgba(255, 255, 255, 0.13);
                position: absolute;
                transform: translate(-50%, -50%);
                top: 50%;
                left: 50%;
                border-radius: 10px;
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
                padding: 50px 35px;
            }

            form * {
                font-family: 'Poppins', sans-serif;
                color: #ffffff;
                letter-spacing: 0.5px;
                outline: none;
                border: none;
            }

            form h3 {
                font-size: 32px;
                font-weight: 500;
                line-height: 42px;
                text-align: center;
            }

            label {
                display: block;
                margin-top: 30px;
                font-size: 16px;
                font-weight: 500;
                float: right;
                margin-bottom: 5px
            }

            input {
                display: block;
                height: 37px;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.07);
                border-radius: 3px;
                padding: 0 10px;
                margin-top: 8px;
                font-size: 14px;
                font-weight: 300;
            }

            ::placeholder {
                color: #e5e5e5;
                float: right;
                margin-top: 7px
            }

            button {
                margin-top: 50px;
                width: 100%;
                background-color: #ffffff;
                padding: 15px 0;
                font-size: 18px;
                font-weight: 600;
                border-radius: 5px;
                cursor: pointer;
            }

            .options {
                margin-top: 30px;
                display: flex;
            }

            .options a {
                background: red;
                width: 150px;
                border-radius: 3px;
                padding: 5px 10px 10px 5px;
                background-color: rgba(255, 255, 255, 0.27);
                color: #eaf0fb;
                text-align: center;
                text-decoration: none;
            }

            .options a:hover {
                background-color: rgba(255, 255, 255, 0.47);
            }

            .options .fb {
                margin-left: 25px;
            }
            .options .fb {
                margin-left: 25px;
            }
        </style>
    </head>

    <body>
        <div class="background">
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <form method="POST" action="{{ route('home.login') }}">
            @csrf
            <h3>ورود</h3>

            <label for="email">:ایمیل</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="...ایمیل خود را وارد کنید" id="email">
            @error('email')
                <div class="alert alert-danger text-center">
                    {{ $message }}
                </div>
            @enderror

            <label for="password">:رمز عبور</label>
            <input type="password" name="password" value="{{ old('password') }}" placeholder="...رمز عبور خود را وارد کنید" id="password">
            @error('password')
                <div class="alert alert-danger text-center">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-check form-control row">
                <label for="rememberMe">منو یادت نره!</label>
                <input name="rememberMe" style="display: inline;width: 25px" type="checkbox" id="rememberMe" {{ old('rememberMe') ? 'checked' : '' }}>
            </div>

            <button style="background-color: #1e7e34" type="submit">ورود</button>

            <div class="options">
                <a class="go text-center" href="#">رمز عبور خود را فراموش کرده اید؟</a>
                <a class="go text-center" href="#">ورود با رمز یکبار مصرف</a>
                <a class="fb text-center" href="{{ route('home.register') }}">ثبت نام</a>
                <a class="go text-center" href="{{ route('home.redirectToProvider', ['provider' => 'google']) }}">ورود با اکانت گوگل</a>
            </div>

        </form>
    </body>

</html>
