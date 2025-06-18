@extends('layout')

@section('title', 'إدارة المشاريع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة المشاريع</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> مشروع جديد
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h5>قائمة المشاريع</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="بحث...">
                    <select name="status" class="form-select me-2">
                        <option value="">جميع الحالات</option>
                        <option value="pending">معلق</option>
                        <option value="in_progress">قيد التنفيذ</option>
                        <option value="completed">مكتمل</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المشروع</th>
                        <th>العميل</th>
                        <th>نوع الخدمة</th>
                        <th>الحالة</th>
                        <th>تاريخ التسليم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->client->name }}</td>
                        <td>{{ $project->service_type }}</td>
                        <td>
                            <span class="badge bg-{{ $project->status_color }}">
                                {{ $project->status_text }}
                            </span>
                        </td>
                        <td>{{ $project->deadline?->format('Y-m-d') ?? 'غير محدد' }}</td>
                        <td>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد مشاريع مسجلة</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush