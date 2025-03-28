<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý Oneship')</title>
    <link rel="stylesheet" href="{{ asset('/custom.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>

</head>

<body>
    <div class="container mt-5">

        @yield('content')

    </div>
    <script>
        $(document).ready(function() {

            fetchShipments(1)

            // fetchShipments(currentPage);
        });

        $(document).on("click", "#pagination .page-link", function(e) {
            e.preventDefault();
            let page = $(this).data("page");
            if (page) {
                fetchShipments(page);
            }
        });

        
        $("#shipmentType").change(function() {
            currentPage = 1;
            fetchShipments(currentPage);
        });

        $("#searchByIdButton").click(function(event) {
            event.preventDefault();
            let id = $("#searchByIdInput").val().trim();

            if (!id) {
                alert("Vui lòng nhập ID");
                return;
            }

            console.log(`Searching ID = ${id}`);

            $.ajax({
                url: `/api/shipments/${id}`,
                type: "GET",
                success: function(shipment) {

                    let row = `
                            <tr>
                                    <td>1</td>
                                    <td>${shipment.e1_code ?? '-'}</td>
                                    <td>${shipment.release_date ?? '-'}</td>
                                    <td>${shipment.chargeable_volumn ?? '-'}</td>
                                    <td>${shipment.main_charge ?? '-'}</td>
                                    <td>${shipment.receiver ?? '-'}</td>
                                    <td>${shipment.recipient_address ?? '-'}</td>
                                    <td>${shipment.phone_number ?? '-'}</td>
                                    <td>${shipment.reference_number ?? '-'}</td>
                                    <td>${shipment.file_name ?? '-'}</td>
                            </tr>`;
                    $("#shipmentTableBody").html(row);
                    $("#pagination").html('');
                },
                error: function(xhr) {
                    alert("Lỗi: " + xhr.responseJSON.message);
                }
            });
        });
        $("#cleaSearchById").click(function(event) {
            event.preventDefault();
            $("#searchByIdInput").val('');
        });

        function setupPagination(total, currentPage, totalPages) {
            let paginationHtml = "";

            if (totalPages > 1) {
                paginationHtml += `<li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="1">«</a>
            </li>`;
                paginationHtml += `<li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">‹</a>
            </li>`;

                paginationHtml += `<li class="page-item disabled">
                <span class="page-link">${currentPage} / ${totalPages}</span>
            </li>`;

                paginationHtml += `<li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">›</a>
            </li>`;
                paginationHtml += `<li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${totalPages}">»</a>
            </li>`;
            }

            $("#pagination").html(paginationHtml);
        }

        function fetchShipments(page) {
            let type = $("#shipmentType").val();
            console.log('Fetching shipment type: ${type},page:${page}');
            $.ajax({
                url: `/api/shipments`,
                type: "GET",
                data: {
                    type: type,
                    page: page
                },
                success: function(response) {
                    console.log("API reponse:", response);
                    let rows = "";
                    if (response.data.length === 0) {
                        rows =
                            '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
                    } else {
                        $.each(response.data, function(shipment, index) {

                            rows += `
                                    <tr>
                                        <td>${(page - 1) * 1000 + shipment + 1}</td>
                                        <td>${index.e1_code ?? '-'}</td>
                                        <td>${index.release_date ?? '-'}</td>
                                        <td>${index.chargeable_volumn ?? '-'}</td>
                                        <td>${index.main_charge ?? '-'}</td>
                                        <td>${index.receiver ?? '-'}</td>
                                        <td>${index.recipient_address ?? '-'}</td>
                                        <td>${index.phone_number ?? '-'}</td>
                                        <td>${index.reference_number ?? '-'}</td>
                                        <td>${index.file_name ?? '-'}</td>
                                    </tr>`;
                        });
                    }
                    $("#shipmentTableBody").html(rows);

                    setupPagination(response.total, response.current_page, response.last_page);
                },
                error: function(xhr) {
                    alert("Lỗi: " + xhr.responseJSON.message);
                }
            });
        }
        // $("#loadShipments").click(function() {
        //     let type = $("#shipmentType").val();
        //     currentPage = 100;
        //     fetchShipments(type, currentPage);
        // });
    </script>
</body>
</html>
