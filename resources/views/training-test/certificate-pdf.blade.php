<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: A4 landscape;
    margin: 0;
}

html, body {
    width: 1123px;
    height: 900px;
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: DejaVu Sans, sans-serif;
}

.certificate {
    width: 1123px;
    height: 790px;
    position: relative;
    background-image: url("{{ public_path('assets/certificates/certificate-bg.png') }}");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}

.content {
    position: absolute;
    top: 42%;
    left: 50%;
    transform: translate(-50%, -50%); /* 🔥 true center */
    text-align: center;
    width: 100%;
    padding: 0 0px;
}

h3, p, div {
    margin: 0;
    padding: 0;
}

.test-title {
    font-size: 22px;
    font-weight: bold;
    margin-top: 6px;
}

.certificate-name {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 80px;
}

.test-title {
    font-size: 24px;
    font-weight: 600;
}
</style>
</head>

<body>

<div class="certificate">
    <div class="content">
        <h1 class="certificate-name">
            {{ auth()->user()->employee->full_name }}
            ({{ $testUser->user->employee->department->department ?? 'Department' }})
        </h1>

        <div class="test-title">
            {{ $testUser->test->title }}
        </div>
    </div>
</div>

</body>
</html>
