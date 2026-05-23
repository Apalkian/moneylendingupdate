<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: radial-gradient(circle at top left, #1f2937 0%, #101214 45%, #0b0f14 100%);
            color: #e5e7eb;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark mb-4 border-bottom border-secondary" style="background:#121820;">
        <div class="container py-1">
            <a class="navbar-brand fw-bold text-warning" href="{{ route('dashboard') }}">MoneyLending Terminal</a>

            <div class="d-flex align-items-center gap-2 order-lg-3">
                @auth
                    <span class="badge text-bg-dark border border-secondary px-3 py-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm fw-bold">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                @endauth
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav gap-1">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="nav-link text-light" href="{{ route('dashboard') }}">Dashboard</a>
                            <a class="nav-link text-light" href="{{ route('borrowers.index') }}">Borrowers</a>
                            <a class="nav-link text-light" href="{{ route('loans.create') }}">New Loan</a>
                            <a class="nav-link text-light" href="{{ route('payments.create') }}">Post Payment</a>
                            <a class="nav-link text-light" href="{{ route('additional-capital.create') }}">Add Capital</a>
                            <a class="nav-link text-light" href="{{ route('ledger') }}">Ledger</a>
                        @else
                            <a class="nav-link text-light" href="{{ route('borrower.portal') }}">Borrower Portal</a>
                        @endif
                    @else
                        <a class="nav-link text-light" href="{{ route('login') }}">Login</a>
                        <a class="nav-link text-light" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>

                <div class="ms-auto"></div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="rounded-3 border border-secondary-subtle bg-dark bg-opacity-50 p-3 p-md-4 text-light shadow-lg">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
