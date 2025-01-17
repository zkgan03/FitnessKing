<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
    <script>
        // Automatically submit the form
        document.getElementById('logout-form').submit();
    </script>
</body>
</html>
