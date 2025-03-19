@extends('layouts.app')

@section('title', 'Danh sách EMS')

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
        <a href="/oneship">Danh sách EMS</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Đăng xuất</button>
        </form>
    </div>
    <h3 class="mt-5">Danh sách VNPOST</h3>
    <form action="{{ route('vnpost.index') }}" method="GET" class="mb-3">
        <div class="search-bar">
            <div class="total-count">
                <strong>Tổng số: </strong>{{ number_format($totalRows) }}
            </div>
            <div class="search-container">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo Mã E1..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <a href="/vnpost"><button type="button" class="btn cancel">Xóa tìm kiếm</button></a>
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
                    <th>Số hiệu</th>
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
            <tbody>
                @forelse ($vnpost as $vnpost)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vnpost->e1_code }}</td>
                        <td>{{ $vnpost->release_date }}</td>
                        <td>{{ $vnpost->chargeable_volumn }}</td>
                        <td>{{ $vnpost->main_charge }}</td>
                        <td>{{ $vnpost->receiver }}</td>
                        <td>{{ $vnpost->recipient_address }}</td>
                        <td>{{ $vnpost->phone_number }}</td>
                        <td>{{ $vnpost->reference_number }}</td>
                        <td>{{ $vnpost->file_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-container d-flex justify-content-center">
        @if ($vnpost instanceof \Illuminate\Pagination\LengthAwarePaginator && $vnpost->lastPage() > 1)
            <ul class="pagination">
                <li class="page-item {{ $vnpost->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $vnpost->url(1) }}">«</a>
                </li>
                <li class="page-item {{ $vnpost->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $vnpost->previousPageUrl() }}">‹</a>
                </li>

                <li class="page-item disabled">
                    <span class="page-link">{{ $vnpost->currentPage() }} / {{ $vnpost->lastPage() }}</span>
                </li>

                <li class="page-item {{ $vnpost->currentPage() == $vnpost->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $vnpost->nextPageUrl() }}">›</a>
                </li>
                <li class="page-item {{ $vnpost->currentPage() == $vnpost->lastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $vnpost->url($vnpost->lastPage()) }}">»</a>
                </li>
            </ul>
        @endif
    </div>

    </div>
    </body>

@endsection
