<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">  
    <title>Thank You</title>
    <script>
        var seconds = 10; // Total seconds for the countdown
        var countdown = setInterval(function() {
            document.getElementById('timer').textContent = seconds;
            seconds--;
            if (seconds < 0) {
                clearInterval(countdown);
                window.location.href = "logout.php"; // Redirect after countdown ends
            }
        }, 1000); // Update every second (1000 milliseconds)
    </script>
</head>
<body>
    <h2>Thank You!</h2>
    <p>User Details has been submitted successfully.</p>
    <p>You will be logged out in <span id="timer">10</span> seconds.</p>
</body>
</html>
