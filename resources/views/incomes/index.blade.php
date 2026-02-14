@extends('layouts.app')

@section('title', 'Incomes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Incomes</h3>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" onclick="confirmBulkDelete()" disabled>
                        <i class="bi bi-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                    <a href="{{ route('incomes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Income</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form id="bulkDeleteForm" action="{{ route('incomes.bulk-destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>ID</th>
                                    <th>Amount</th>
                                    <th>Source</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incomes as $income)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input income-checkbox" name="ids[]" value="{{ $income->id }}" onchange="updateBulkDeleteButton()">
                                        </td>
                                        <td>{{ $income->id }}</td>
                                        <td>{{ $income->amount }}</td>
                                        <td>{{ $income->source }}</td>
                                        <td>{{ $income->date }}</td>
                                        <td>{{ $income->notes }}</td>
                                        <td>
                                            <a href="{{ route('incomes.show', $income) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('incomes.edit', $income) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.income-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateBulkDeleteButton();
    }

    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.income-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');

        selectedCount.textContent = checkboxes.length;
        bulkDeleteBtn.disabled = checkboxes.length === 0;
    }

    function confirmBulkDelete() {
        const checkboxes = document.querySelectorAll('.income-checkbox:checked');
        const count = checkboxes.length;

        if (count === 0) {
            alert('Please select at least one income to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete ' + count + ' income(s)? This action cannot be undone.')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }
</script>
@endsection
