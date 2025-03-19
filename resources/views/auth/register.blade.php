<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('stylelog.css') }}">
    <title>Đăng ký tài khoản</title>
</head>
<body>
    <div class="form-container">
        <h2>Đăng ký tài khoản</h2>
    
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
    
        @if($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif
    
        <form action="{{ url('/register') }}" method="POST">
            @csrf
            <label for="name">Tên đăng nhập:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
    
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
    
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" required>
    
            <label for="password_confirmation">Xác nhận mật khẩu:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
    
            <button type="submit">Đăng ký</button>
        </form>
    
        <p>Đã có tài khoản? <a href="{{ url('/login') }}">Đăng nhập</a></p>
    </div>
    
</body>
</html>
