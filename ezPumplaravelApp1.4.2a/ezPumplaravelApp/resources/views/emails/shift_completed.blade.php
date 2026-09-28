<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Shift Closure Alert</title>

</head>
<body style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;line-height:1.6;color:#333333;background-color:#f5f7fa;margin:0;padding:20px;">
<div class="email-container" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
    <div class="header" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:white;padding:25px;text-align:center;">
        <div class="alert-icon" style="font-size:28px;margin-bottom:10px;display:block;">🚨</div>
        <h1 class="alert-title" style="font-size:22px;font-weight:700;margin:0;">Shift Closure Missed</h1>
        <p>Immediate Action Required</p>
    </div>
    <div class="content" style="padding:25px;">
        <p>Dear Sir,</p>
        <div class="highlight-box" style="background-color:#fff8f8;border-left:4px solid #e74c3c;padding:18px;margin:20px 0;border-radius:0 4px 4px 0;">
            <p>As per our records, the shift scheduled to close at <strong>{{$end_time}}</strong> on <strong>{{$start_name}}</strong> at <strong>{{ucfirst($station_name)}} / {{ucfirst($station_location)}}</strong> has <strong>not been closed</strong>, and the scheduled time has now passed.</p>
        </div>
        <div class="detail-card" style="background-color:#f8f9fa;border-radius:6px;padding:15px;margin:20px 0;">
            <div class="detail-row" style="display:flex;margin-bottom:12px;">
                <div class="detail-icon" style="width:24px;color:#e74c3c;font-size:18px;">🕒</div>
                <div class="detail-label" style="font-weight:600;color:#555555;width:150px;">Scheduled Close Time:</div>
                <div>{{$end_time}}</div>
            </div>
            <div class="detail-row" style="display:flex;margin-bottom:12px;">
                <div class="detail-icon" style="width:24px;color:#e74c3c;font-size:18px;">⛽</div>
                <div class="detail-label" style="font-weight:600;color:#555555;width:150px;">Station:</div>
                <div>{{ucfirst($station_name)}}</div>
            </div>
            <div class="detail-row" style="display:flex;margin-bottom:12px;">
                <div class="detail-icon" style="width:24px;color:#e74c3c;font-size:18px;">🧑💼</div>
                <div class="detail-label" style="font-weight:600;color:#555555;width:150px;">Operator:</div>
                <div>{{ucfirst($shift_name)}}</div>
            </div>
            <div class="detail-row" style="display:flex;margin-bottom:12px;">
                <div class="detail-icon" style="width:24px;color:#e74c3c;font-size:18px;">📅</div>
                <div class="detail-label" style="font-weight:600;color:#555555;width:150px;">Date:</div>
                <div>{{$start_name}}</div>
            </div>
        </div>
        <div class="action-alert" style="background-color:#fff3f3;border:1px solid #ffdddd;padding:16px;border-radius:6px;margin:25px 0;">
            <p class="action-text" style="color:#e74c3c;font-weight:600;">🚨 <strong>Action Required Immediately:</strong></p>
            <p>Please log in to the Ez-PUMP system and complete the shift closure process without further delay.</p>
            <center><a class="btn" href="#" style="display:inline-block;background:linear-gradient(135deg,#e74c3c,#c0392b);color:white;text-decoration:none;padding:12px 25px;border-radius:6px;font-weight:600;margin:15px 0;">LOGIN TO SYSTEM</a></center>
        </div>
        <p>If the shift has already been closed manually or through Ez-PUMP and you're receiving this message in error, please inform our support team immediately.</p>
        <div class="contact-info" style="margin-top:25px;padding-top:15px;border-top:1px solid #eeeeee;">
            <p><strong>📞 Support:</strong> +92 311 1141333</p>
            <p><strong>✉️ Email:</strong> info@ez-pump.com</p>
        </div>
        <p>Regards,<br/>
            <strong>Ez-PUMP Monitoring Team</strong><br/>
            🌐 <a href="https://ez-pump.com/">https://ez-pump.com/</a> | ✉️ Email: info@ez-pump.com</p>
    </div>
    <div class="footer" style="text-align:center;padding:20px;font-size:12px;color:rgba(119,119,119,0.86);background-color:#f8f9fa;">
        <p><em>Note: This is an automated system notification. Please do not reply to this email.</em></p>
    </div>
</div>
</body>
</html>
