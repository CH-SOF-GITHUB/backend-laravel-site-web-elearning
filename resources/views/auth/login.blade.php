<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        async function login(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const response = await fetch(event.target.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            console.log('Response:', response); // Log the response object
            const data = await response.json();
            console.log('Data:', data); // Log the response data

            if (response.ok) {
                // Ensure the token is available
                if (data.access_token) {
                    // Store the token in local storage
                    localStorage.setItem('token', data.access_token);
                    console.log('Token stored:', data.access_token);

                    // Redirect to dashboard
                    window.location.href = '/dashboard';
                } else {
                    alert('No access token received.');
                }
            } else {
                alert(data.error || 'Login failed'); // Use 'data.error' for consistency
            }
        }
    </script>
</head>

<body>
    <h1>Login</h1>
    <form action="{{ route('login.post') }}" method="POST" onsubmit="login(event)">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</body>

</html>
