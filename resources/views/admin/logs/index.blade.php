@extends('layouts.app')

@section('title', 'Admin · Logs')

@section('content')
<header class="hero">
    <div>
        <p class="eyebrow">Admin</p>
        <h1>Application logs</h1>
        <p class="lead">Showing the latest {{ $maxLines }} lines from {{ $path }}.</p>
    </div>
    <a class="link" href="{{ route('admin.home') }}">Back to admin</a>
</header>

@if ($missing)
    <div class="banner error">Log file not found at {{ $path }}.</div>
@elseif (empty($lines))
    <div class="banner">No log entries found.</div>
@else
    <div class="card" style="overflow:auto;max-height:70vh;">
        <pre style="margin:0;white-space:pre-wrap;word-break:break-word;">{{ implode("\n", $lines) }}</pre>
    </div>
@endif
@endsection
