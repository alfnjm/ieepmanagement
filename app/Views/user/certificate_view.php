<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate for <?= esc($eventTitle) ?></title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            width: 100vw;
            height: 100vh;
        }
        .certificate {
            width: 90%;
            max-width: 1100px;
            height: 90%;
            max-height: 750px;
            margin: 5vh auto;
            border: 10px solid #c0a060; 
            padding: 50px;
            text-align: center;
            background-color: #fffdf9; 
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .header {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4b0082; 
            margin-bottom: 20px;
        }
        .title {
            font-family: 'Georgia', serif;
            font-size: 3rem;
            color: #d4af37; 
            margin: 40px 0;
        }
        .recipient-label {
            font-size: 1.2rem;
            color: #333;
        }
        .recipient-name {
            font-size: 4rem;
            font-family: 'Times New Roman', serif;
            color: #3b82f6; 
            border-bottom: 3px solid #3b82f6;
            display: inline-block;
            margin: 10px 0 30px;
            font-weight: 700;
        }
        .event-info {
            font-size: 1.5rem;
            color: #555;
            line-height: 1.6;
        }
        .details-box {
            background: #f0f8ff;
            padding: 10px;
            border-radius: 5px;
            border: 1px dashed #3b82f6;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #333;
        }
        .footer-details {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            display: flex;
            justify-content: space-around;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 10px;
            padding-top: 5px;
            font-size: 0.9rem;
        }
        /* Hide all print-irrelevant content */
        @media print {
            .certificate {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>

<div class="certificate">
    <div class="header">KEMENTERIAN PENDIDIKAN TINGGI</div>
    <div class="header">JABATAN PENDIDIKAN POLITEKNIK DAN KOLEJ KOMUNITI</div>
    
    <h1 class="title">Certificate of Attendance</h1>
    
    <p class="recipient-label">This certificate is proudly presented to</p>
    
    <div class="recipient-name"><?= esc($userName) ?></div>
    
    <div class="event-info">
        <p>for their successful participation and attendance in the program:</p>
        <p style="font-size: 2rem; font-weight: bold; color: #4b0082; margin-top: 10px;"><?= esc($eventTitle) ?></p>
        <p>Held on: <?= esc(date('F d, Y', strtotime($eventDate))) ?></p>
    </div>

    <div class="details-box">
        Student/Staff ID: **<?= esc($userId) ?>**
    </div>

    <div class="footer-details">
        <div>
            <div style="height: 50px;"></div>
            <div class="signature-line">Program Organizer</div>
        </div>
        <div>
            <div style="height: 50px;"></div>
            <div class="signature-line">IEEP Coordinator</div>
        </div>
    </div>
</div>

<script>
    // Automatically trigger print dialog on load
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>