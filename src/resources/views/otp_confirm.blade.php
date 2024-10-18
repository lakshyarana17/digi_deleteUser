<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm OTP</title>
    <style>
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Confirm OTP</h1>
        <form id="otpConfirmForm" method="POST" action="{{ route('otp.confirm') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm OTP</button>
            <div id="timer" class="mt-3"></div>
            <div id="responseMessage" class="mt-3"></div>
        </form>
        <div id="resendLinkContainer" class="mt-3" style="display: none;">
            <a href="#" id="resendLink" onclick="requestNewOtp()">Resend OTP</a>
        </div>
    </div>

    <script>
        let timeLeft = 60;
        const timerElement = document.getElementById('timer');
        const resendLinkContainer = document.getElementById('resendLinkContainer');
        const resendLink = document.getElementById('resendLink');

        const timerInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.innerHTML = "OTP has expired.";
                document.getElementById('otpConfirmForm').reset(); 
                document.getElementById('otpConfirmForm').querySelector('button').disabled = true; 
                resendLinkContainer.style.display = 'block'; 
            } else {
                timerElement.innerHTML = `Time remaining: ${timeLeft} seconds`;
                timeLeft--;
            }
        }, 1000);

        function requestNewOtp() {
            const email = '{{ session('email') }}'; 
            fetch('/request-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    document.getElementById('responseMessage').innerText = data.message;
                    timeLeft = 60;
                    timerElement.innerHTML = `Time remaining: ${timeLeft} seconds`;
                    resendLinkContainer.style.display = 'none'; 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('responseMessage').innerText = 'Error resending OTP.';
            });
        }
    </script>
</body>
</html>
