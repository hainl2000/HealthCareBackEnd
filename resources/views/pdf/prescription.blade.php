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
    table th {
        font-size: 12px !important;
    }
    table td {
        font-size: 10px !important;
    }
</style>
<body>
<div class="container mt-5 mb-2">
    <div class="header">
        <p class="title">Hệ Thống HCARE</p>
        <p class="name pb-3 pt-3">Bác sĩ {{ $data['doctorName'] }}</p>
        <span> Ngày khám: 10/12/2000 </span>
    </div>
    <div class="body pt-2 pb-2">
        <div class="d-flex col-12 form mt-2">
            <div class="col-7">
                <div class="form-text">
                    <span>Tên bệnh nhân: {{ $data['patientName'] }}</span>
                </div>
                <div class="form-text">
                    <span>Address: {{ $data['patientAddress'] }}</span>
                </div>
            </div>
            <div class="col-5">
                <div class="d-flex">
                    <div class="form-text">
                        <span>Age: 18</span>
                    </div>
                    <div class="form-text">
                        <span>Gender: {{ $data['patientGender'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        Chẩn đoán:
        <div class="d-flex col-12 form mt-2">
            <div class="col-5">
                <div class="d-flex">
                    <div class="form-text">
                        <span>Chẩn đoán: {{ $data['diagnose'] }}</span>
                    </div>
                    <div class="form-text">
                        <span>Lưu ý: {{ $data['additionalDirection'] }}</span>
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
                    @foreach($data['drugs'] as $drug)
                        <tr>
                            <th scope="row">{{ $drug['drugName'] }}</th>
                            <td>{{ $drug['drugUnit'] }}</td>
                            <td>{{ $drug['dosages'] }}</td>
                            <td>{{ $drug['numberPerTime'] }}</td>
                            <td>{{ $drug['meals'] }}</td>
                            <td>{{ $drug['timesText'] }}</td>
                            <td>{{ $drug['note'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="footer pt-2">
        <div class="form-text">
            <span>Doctor's signature:</span>
        </div>
    </div>
</div>
</body>
</html>
