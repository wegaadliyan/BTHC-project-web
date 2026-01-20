
@extends('layouts.admin')

@section('content')
<div class="admin-contacts">
    <h1>Contact Messages</h1>

    @if(session('success'))
        <div style="padding:10px;background:#e6ffed;border:1px solid #b7f0c9;margin-bottom:12px">{{ session('success') }}</div>
    @endif

    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #ddd">
                <th style="padding:8px">#</th>
                <th style="padding:8px">Name</th>
                <th style="padding:8px">Email</th>
                <th style="padding:8px">Phone</th>
                <th style="padding:8px">Message</th>
                <th style="padding:8px">Sent At</th>
                <th style="padding:8px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $msg)
            <tr style="border-bottom:1px solid #f0f0f0">
                <td style="padding:8px">{{ $msg->id }}</td>
                <td style="padding:8px">{{ $msg->name }}</td>
                <td style="padding:8px">{{ $msg->email }}</td>
                <td style="padding:8px">{{ $msg->phone ?? '-' }}</td>
                <td style="padding:8px;max-width:480px;white-space:pre-wrap">{{ $msg->message }}</td>
                <td style="padding:8px">{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                <td style="padding:8px">
                    <a href="{{ route('admin.contacts.show', $msg->id) }}" style="margin-right:8px;display:inline-block;padding:6px 10px;background:#3490dc;color:#fff;border-radius:4px;text-decoration:none">View</a>
                    <form method="POST" action="{{ route('admin.contacts.destroy', $msg->id) }}" style="display:inline-block" onsubmit="return confirm('Hapus pesan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding:6px 10px;background:#e3342f;color:#fff;border:none;border-radius:4px;cursor:pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px">{{ $messages->links() }}</div>
</div>

@endsection
