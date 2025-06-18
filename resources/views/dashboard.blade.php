@extends('layout')

@section('title', 'لوحة التحكم')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">العملاء</h5>
                <p class="card-text display-4">{{ $clientsCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">المشاريع النشطة</h5>
                <p class="card-text display-4">{{ $activeProjectsCount }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>آخر المشاريع</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>العميل</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProjects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->client->name }}</td>
                    <td><span class="badge bg-{{ $project->status_color }}">{{ $project->status_text }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection