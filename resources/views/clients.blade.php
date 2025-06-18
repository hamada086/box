@extends('layout')

@section('title', 'إدارة العملاء')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة العملاء</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">إضافة عميل جديد</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td><span class="badge bg-{{ $client->status_badge }}">{{ $client->status_text }}</span></td>
                    <td>
                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info">عرض</a>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $clients->links() }}
    </div>
</div>
@endsection