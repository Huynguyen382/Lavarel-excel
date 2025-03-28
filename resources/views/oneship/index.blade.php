@extends('layouts.app')

@section('title', 'Danh sách Oneship')

@section('content')
    <div class="upload-section">
        <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="file" name="excelFiles[]" class="form-control" multiple required>
                <button type="submit" class="btn btn-success">Nhập Excel</button>
            </div>
        </form>

        <form action="{{ route('export.excel') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="file" name="file" class="form-control" required>
                <button type="submit" class="btn btn-success">Upload và Xử lý</button>
            </div>
        </form>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <p>Xin chào, {{ Auth::user()->name }}</p>Đăng xuất
            </button>
        </form>
    </div>
    <h3 class="mt-5">Danh sách đơn hàng</h3>
    <form action="{{ route('oneship.index') }}" method="GET" class="mb-3">
        <div class="search-bar">
            <div class="total-count">
                <strong>Tổng số: </strong>{{ number_format($totalRows) }}
            </div>
            <div class="d-flex justify-content-between mb-3">
                <select id="shipmentType" class="form-select w-25">
                    <option value="oneships">EMS</option>
                    <option value="vnpost">VNPost</option>
                </select>
            </div>
            <div class="search-container">
                <input type="text" id="searchByIdInput" class="form-control" placeholder="Nhập mã đơn hàng...">
                <button id="searchByIdButton" class="btn btn-primary">Tìm kiếm</button>
                <button id="clearSearchById" class="btn btn-danger">Xóa</button>
            </div>
            
        </div>
    </form>

    @if (session('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif


    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã E1</th>
                    <th>Ngày phát hành</th>
                    <th>Khối lượng tính phí</th>
                    <th>Cước chính</th>
                    <th>Người nhận</th>
                    <th>Địa chỉ nhận</th>
                    <th>Số điện thoại</th>
                    <th>Số Tham Chiếu</th>
                    <th>Nhập từ file</th>
                </tr>
            </thead>
            <tbody id="shipmentTableBody">
                <tr>
                    <td colspan="10" class="text-center">Chưa có dữ liệu</td>
                </tr>
            </tbody>
        </table>

    </div>
    <nav>
        <ul id="pagination" class="pagination justify-content-center" style="padding-top: 20px"></ul>
    </nav>


    </div>

    </body>

@endsection
