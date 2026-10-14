<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'QuickMed') | QuickMed Drug Finder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root { --qm: #0f766e; --qm-dark: #0b5d57; --qm-soft: #e6f4f2; }
    body { background: #f6f8f9; }
    .btn-primary { --bs-btn-bg: var(--qm); --bs-btn-border-color: var(--qm); --bs-btn-hover-bg: var(--qm-dark); --bs-btn-hover-border-color: var(--qm-dark); --bs-btn-active-bg: var(--qm-dark); --bs-btn-active-border-color: var(--qm-dark); --bs-btn-disabled-bg: var(--qm); --bs-btn-disabled-border-color: var(--qm); }
    .btn-outline-primary { --bs-btn-color: var(--qm); --bs-btn-border-color: var(--qm); --bs-btn-hover-bg: var(--qm); --bs-btn-hover-border-color: var(--qm); --bs-btn-active-bg: var(--qm); }
    .text-primary { color: var(--qm) !important; } .bg-primary { background-color: var(--qm) !important; }
    a { color: var(--qm); } .navbar-brand { font-weight: 700; letter-spacing: .3px; }
    .hero { background: linear-gradient(135deg, #0f766e 0%, #0b5d57 100%); color: #fff; }
    .card { border: 1px solid #e3e8ea; border-radius: .9rem; }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; background: var(--qm-soft); color: var(--qm); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .table > :not(caption) > * > * { padding: .75rem .75rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand text-primary" href="{{ route('home') }}"><i class="bi bi-capsule-pill"></i> QuickMed</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Find Medicine</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('pharmacies.index') }}">Pharmacies</a></li>
        @auth
          @if(auth()->user()->isCustomer())<li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">My Orders</a></li>@endif
          @if(auth()->user()->isOwner())<li class="nav-item"><a class="nav-link" href="{{ route('pharmacy.dashboard') }}">Pharmacy Dashboard</a></li>@endif
          @if(auth()->user()->isAdmin())<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>@endif
        @endauth
      </ul>
      <div class="d-flex gap-2 align-items-center">
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
        @else
          <span class="small text-muted d-none d-lg-inline">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary btn-sm">Logout</button></form>
        @endguest
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @yield('content')
</main>

<footer class="border-top bg-white py-4 mt-5">
  <div class="container small text-muted d-flex flex-column flex-md-row justify-content-between gap-2">
    <span>&copy; {{ date('Y') }} QuickMed. Search before you move.</span>
    <span>QuickMed does not diagnose illnesses or prescribe medicines. Always follow a pharmacist's or doctor's advice.</span>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
