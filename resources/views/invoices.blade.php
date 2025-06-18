@extends('layout')

@section('title', 'الفواتير')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة الفواتير</h2>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-file-invoice"></i> فاتورة جديدة
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h5>سجل الفواتير</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="بحث...">
                    <select name="status" class="form-select me-2">
                        <option value="">جميع الحالات</option>
                        <option value="paid">مدفوعة</option>
                        <option value="unpaid">غير مدفوعة</option>
                        <option value="overdue">متأخرة</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>تاريخ الإصدار</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $invoice->client->name }}</td>
                        <td>{{ $invoice->issue_date->format('Y-m-d') }}</td>
                        <td @if($invoice->isOverdue) class="text-danger fw-bold" @endif>
                            {{ $invoice->due_date->format('Y-m-d') }}
                        </td>
                        <td>{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                        <td>
                            @if($invoice->status == 'paid')
                            <span class="badge bg-success">مدفوعة</span>
                            @elseif($invoice->isOverdue)
                            <span class="badge bg-danger">متأخرة</span>
                            @else
                            <span class="badge bg-warning text-dark">غير مدفوعة</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>عرض {{ $invoices->count() }} من أصل {{ $invoices->total() }} فاتورة</div>
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection