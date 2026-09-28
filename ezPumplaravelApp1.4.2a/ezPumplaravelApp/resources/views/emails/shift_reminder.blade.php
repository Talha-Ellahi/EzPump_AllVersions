<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shift End Reminder</title>
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f5f7ff; color: #212529; margin: 0; padding: 0;">

<div style="max-width:600px; margin:20px auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(67,97,238,0.15);">

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #4361ee, #3f37c9); color:white; padding:30px 20px; text-align:center; position:relative;">
        <div style="position:relative; z-index:2;">
            <div style="display:inline-block; background-color:#f72585; color:white; padding:8px 16px; border-radius:50px; font-weight:600; font-size:14px; margin-bottom:15px; box-shadow:0 4px 12px rgba(247,37,133,0.3);">
                REMINDER
            </div>
            <h2 style="margin:0; font-weight:600; font-size:24px;">Shift Ending Soon</h2>
            <div style="font-size:28px; font-weight:700; margin:15px 0;">⏳ {{$start_name}}</div>
            <p style="margin:0; opacity:0.9;">Your shift closes at <strong>{{$end_time}}</strong></p>
        </div>
    </div>

    <!-- Body -->
    <div style="padding:30px;">
        <p>Hello <strong>{{ucfirst($user_name)}}</strong>,</p>
        <p>This is an automated reminder that your current shift at <strong>{{$station_name}}</strong> is scheduled to close. Please complete all closing procedures.</p>

        <!-- Shift Details -->
        <div style="background-color:#f8f9fa; border-radius:12px; padding:20px; margin-bottom:25px; box-shadow:0 5px 15px rgba(0,0,0,0.03);">
            <h4 style="margin-top:0; color:#4361ee;">Shift Details</h4>

            <table style="width:100%; margin-top:15px;">
                <tr>
                    <td style="padding:8px 0;"><strong>⛽ Station:</strong></td>
                    <td>{{ucfirst($station_name)}} / {{ucfirst($station_location)}}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;"><strong>👤 Operator:</strong></td>
                    <td>{{ucfirst($user_name)}}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;"><strong>🕒 Closing Time:</strong></td>
                    <td>{{$end_time}}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;"><strong>📅 Date:</strong></td>
                    <td>{{$start_name}}</td>
                </tr>
            </table>
        </div>

        <!-- Call to Action -->
        <div style="text-align:center; margin:20px 0;">
            <a href="#" style="background:linear-gradient(135deg, #4361ee, #3f37c9); padding:12px 24px; color:white; text-decoration:none; font-weight:600; border-radius:8px; display:inline-block;">
                Complete Shift Closing
            </a>
        </div>

        <p style="font-size: 14px; color:#6c757d;">Please ensure that the shift is properly closed on time to avoid delays or discrepancies.</p>

        <!-- Support -->
        <h4 style="margin-top:30px; margin-bottom:10px;">Need Help?</h4>
        <p style="margin:0;">📞 +92 311 1141333</p>
        <p style="margin:0;">✉️ info@ez-pump.com</p>
    </div>

    <!-- Footer -->
    <div style="background-color:#212529; color:white; padding:20px; text-align:center; font-size:12px;">
        <strong style="font-size:16px;">EZ-PUMP</strong><br>
        <span style="opacity:0.8;">Fuel Management System</span>
        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0;">
        <p style="margin:5px 0;">
            <a href="https://ez-pump.com/" style="color:#4895ef; text-decoration:none;">Website</a> |
            <a href="#" style="color:#4895ef; text-decoration:none;">Support</a> |
            <a href="#" style="color:#4895ef; text-decoration:none;">Privacy Policy</a>
        </p>
        <p style="margin-top:15px; opacity:0.6;">This is an automated notification. Please do not reply to this email.</p>
    </div>

</div>

</body>
</html>
