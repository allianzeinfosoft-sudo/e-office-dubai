<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $mail_title }}</title>
</head>
<body>
    <div class="card-body">
        <div class="badge w-100 bg-success mb-3">
            <h5 class="mb-0 text-white">{{ $mail_title }}</h5>
        </div>
        <p class="mb-0">Dear,</p>
        <p>{{  $mail_to  }}</p>
        <p>This is to confirm that the following book has been successfully returned to the e-Library:</p>
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td style="padding: 8px; font-weight: bold;">Book Title</td>
                <td style="padding: 8px;">{{ $book_title }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Author</td>
                <td style="padding: 8px;">{{ $author }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Issued On</td>
                <td style="padding: 8px;">{{ $issue_date }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Returned On</td>
                <td style="padding: 8px;">{{ $return_date }}</td>
            </tr>
        </table>
        <p>Thank you for returning the book on time.</p>
        <p>If you wish to borrow another book, please visit the <strong>e-office portal</strong>.</p>
        <p>Best regards,</p>
        <p><strong>HR Department</strong><br>
        e-Library Management</p>
        <div class="col-md-5 mt-3 text-end"><span>{{ date('d-m-Y H:i:s') }}</span></div>
        </div>
    </div>
</body>
</html>




