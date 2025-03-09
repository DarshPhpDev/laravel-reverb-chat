<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="container mt-5">
        <h1>Chat Users</h1>
        <ul class="list-group mt-3">
            @foreach ($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('chat', $user->id) }}">{{ $user->name }}</a>
                    <span class="badge {{ $user->isOnline() ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->isOnline() ? 'Online' : 'Offline' }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>