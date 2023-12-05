<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>trang quản lý - vtourism</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        color: white;
        font-family: 'Inter', sans-serif;
        background: #101010;
    }
    .navbar {
        background: #7160d9;
    }
    .active {
        padding-left: 10px;
        padding-right: 10px;
        border-radius: 5px;
        background: white;
        color: #7160d9 !important;
    }

    a {
        color: white;
        text-decoration: none;
    }

    small {
        border-radius: 25px;
    }
    .project_placeholder {
        border-radius: 25px;
        background: #1e1e2e;
        padding: 20px;
        align-items: center;
        margin-bottom: 10px;
    }
</style>

<body>
    <nav class="navbar">
        <div class="container">
            <small class="fw-bold" style="color:white">trang quản lý - vtourism</small>

            <a href="/home" class="{{Route::is('projects') ? "active" : ""}}">
                <i class="fa fa-book"></i> Dự án
            </a>
            <a href="/vtours" class="{{Route::is('vtours') ? "active" : ""}}">
                <i class="fa fa-eye"></i> Vtours
            </a>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false"> Xin chào: <small class="fw-bold">{{ Auth::user()->name }}</small>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            {{ __('đăng xuất') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>