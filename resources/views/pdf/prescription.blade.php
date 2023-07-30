<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="http://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
</head>
<style>
    body, h1 {
        font-family: DejaVu Sans, sans-serif !important;
    }

    .title {
        text-align: center;
        color: #4b4b98;
    }
    .name {
        font-weight: 400;
        font-size: 20px;
        border-bottom: 5px solid #4b4b98;
        width: 100%;
    }
    .form-text {
        white-space: nowrap;
    }
    .form {
        padding: 0;
    }
    .form > div {
        padding: 0;
    }
    .form-text input {
        outline: none;
        border: none;
        border-bottom: 1px dotted black;
        text-align: center;
    }
    .body {
        border-bottom: 5px solid #4b4b98;
        height: 66vh;
    }
    .footer span {
        font-size: 20px;
    }
</style>
<body>
<div class="container mt-5 mb-2">
    <div class="header">
        <p class="title">Hệ Thống HCARE</p>
        <p class="name pb-3 pt-3">Bác sĩ Nguyễn Văn Hà</p>
        <span> Ngày khám: 10/12/2000 </span>
    </div>
    <div class="body pt-2 pb-2">
        <div class="d-flex col-12 form mt-2">
            <div class="col-7">
                <div class="form-text">
                    <span>Tên bệnh nhân: Nguyễn Văn A</span>
                </div>
                <div class="form-text">
                    <span>Address: Tập thể đại học công nghiệp hà nội, tây tựu, bắc từ liêm, hà nội</span>
                </div>
            </div>
            <div class="col-5">
                <div class="d-flex">
                    <div class="form-text">
                        <span>Age: 18</span>
                    </div>
                    <div class="form-text">
                        <span>Gender: Nam</span>
                    </div>
                </div>
            </div>
        </div>
        Chẩn đoán:
        <div class="d-flex col-12 form mt-2">
            <div class="col-5">
                <div class="d-flex">
                    <div class="form-text">
                        <span>Chẩn đoán: AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</span>
                    </div>
                    <div class="form-text">
                        <span>Lưu ý: AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</span>
                    </div>
                </div>
            </div>
        </div>
        Đơn thuốc:
        <div class="mt-5">
            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th scope="col">Tên thuốc</th>
                        <th scope="col">Đơn vị</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Liều dùng</th>
                        <th scope="col">Số lần uống</th>
                        <th scope="col">Thời gian</th>
                        <th scope="col">Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>12</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>Uống sau ăn</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td>Sau hoặc trước ăn</td>
                        <td>@fat</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                    </tr>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td>@fat</td>
                        <td>@fat</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">Paraphetamolllllll</th>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                        <td>@twitter</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="footer pt-2">
        <div class="form-text">
            <span>Doctor's signature:</span>
            <img width="100" height="80" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/avatar/lichhoc-1686773000104.png'))) }}" alt="sign">
        </div>
    </div>
</div>
</body>
</html>
