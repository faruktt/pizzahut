@extends('layouts.admin')
@section('title', 'Add Expense | Italian Pizza Hut Admin')
@section('page-title', 'Record Expense')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Expenses
    </a>

    <form action="{{ route('admin.expenses.store') }}" method="POST">
        @csrf

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-file-invoice-dollar"></i> Expense Details</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expense Category *</label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Expense Title / Description *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. October Shop Rent / DESCO Electricity Bill">
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Amount (৳) *</label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required placeholder="0.00">
                        @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Note / Voucher Ref / Receiver Info</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Additional details or reference note...">{{ old('note') }}</textarea>
                    @error('note')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Save Expense
            </button>
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

