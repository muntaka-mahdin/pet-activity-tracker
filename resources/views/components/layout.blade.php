@props(['title'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · Pet Activity Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container header-row">
            <a href="{{ route('dashboard') }}" class="brand">🐾 Pet Activity Tracker</a>
            <nav class="nav">
                <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>Dashboard</a>
                <a href="{{ route('pets.index') }}" @class(['active' => request()->routeIs('pets.*')])>Pets</a>
                <a href="{{ route('activities.index') }}" @class(['active' => request()->routeIs('activities.*')])>Activities</a>
                <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a>
            </nav>
            <a href="{{ route('activities.create') }}" class="btn btn-light">+ Log activity</a>
        </div>
    </header>

    <main class="container main">
        @if (session('success'))
            <p class="flash" role="status">{{ session('success') }}</p>
        @endif

        {{ $slot }}
    </main>

    <footer class="site-footer">
        <div class="container">Pet Activity Tracker · Laravel {{ app()->version() }}</div>
    </footer>
</body>
</html>
