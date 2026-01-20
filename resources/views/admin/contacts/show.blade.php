@extends('layouts.admin')

@section('content')
<div class="contact-detail">
    <h1>Contact Message #{{ $message->id }}</h1>

    <div style="margin-top:12px">
        <p><strong>Name:</strong> {{ $message->name }}</p>
        <p><strong>Email:</strong> {{ $message->email }}</p>
        <p><strong>Phone:</strong> {{ $message->phone ?? '-' }}</p>
        <p><strong>Sent At:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
        <p style="white-space:pre-wrap;margin-top:16px"><strong>Message:</strong><br>{{ $message->message }}</p>
    </div>

    <div style="margin-top:20px;display:flex;gap:8px">
        <a href="{{ route('admin.contacts') }}" style="padding:8px 12px;background:#ddd;border-radius:6px;text-decoration:none">Back</a>
        <form method="POST" action="{{ route('admin.contacts.destroy', $message->id) }}" onsubmit="return confirm('Hapus pesan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding:8px 12px;background:#e3342f;color:#fff;border:none;border-radius:6px">Delete</button>
        </form>
    </div>
</div>

@endsection
