<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <h1 class="app-title">Pet Activity Tracker</h1>
            <nav>
                <a href="{{ route('activities.index') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('activities.create') }}">Add New Activity</a>
                <!-- Search Form -->
                <form action="{{ route('activities.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search activities..." value="{{ $search ?? '' }}">
                    <button type="submit">Search</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="main-content">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Pet Activity Tracker. All rights reserved.</p>
    </footer>
</body>
</html>
