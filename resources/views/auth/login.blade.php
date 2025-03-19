<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('stylelog.css') }}">
    <title>Đăng nhập</title>
</head>
<body>
    <div class="form-container">
        <h2>Đăng nhập</h2>
    
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
    
        @if($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif
    
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <label>Tên đăng nhập:</label>
            <input type="text" name="name" required>  
    
            <label>Mật khẩu:</label>
            <input type="password" name="password" required>
    
            <button type="submit">Đăng nhập</button>
        </form>
        <p>Chưa có tài khoản? <a href="{{ url('/register') }}">Đăng ký</a></p>
    </div>
</body>
</html>
