@extends('layouts.admin')
@section('title', 'Edit Expense | Italian Pizza Hut Admin')
@section('page-title', 'Edit Expense')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Expenses
    </a>

    <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-pen-to-square"></i> Edit Expense: {{ $expense->expense_number }}</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expense Category *</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $expense->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Expense Title / Description *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $expense->title) }}" required>
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Amount (৳) *</label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount', (float)$expense->amount) }}" required>
                        @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                        @error('expense_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            @php $pm = old('payment_method', $expense->payment_method); @endphp
                            <option value="cash" {{ $pm == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ $pm == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="bkash" {{ $pm == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ $pm == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="card" {{ $pm == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ $pm == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Note / Voucher Ref / Receiver Info</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note', $expense->note) }}</textarea>
                    @error('note')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update Expense
            </button>
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

