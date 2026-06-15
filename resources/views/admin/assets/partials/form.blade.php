{{-- Shared create/edit fields. Expects $asset (Asset|null). --}}
<div class="form-group">
    <label>Asset Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
        value="{{ old('name', $asset->name ?? '') }}" placeholder="e.g. Morupule Coal Mine">
</div>

<div class="form-group">
    <label>Category <span class="text-danger">*</span></label>
    <input type="text" name="category" class="form-control" required
        value="{{ old('category', $asset->category ?? '') }}" placeholder="e.g. Mining, Energy, Infrastructure">
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="4"
        placeholder="Brief description of the government asset">{{ old('description', $asset->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Status <span class="text-danger">*</span></label>
    <select name="status" class="form-control" required>
        <option value="active" {{ old('status', $asset->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $asset->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>

<hr>
<h4 class="mb-3">Investment Terms</h4>
<p class="text-muted">Set how citizens invest in this asset and the returns they earn.</p>

<div class="form-row">
    <div class="form-group col-md-4">
        <label>Investment Amount Type <span class="text-danger">*</span></label>
        <select name="amount_type" class="form-control" required>
            <option value="ranged" {{ old('amount_type', $asset->amount_type ?? 'ranged') === 'ranged' ? 'selected' : '' }}>Ranged (min–max)</option>
            <option value="fixed" {{ old('amount_type', $asset->amount_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed amount</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Minimum Investment (P)</label>
        <input type="number" step="0.01" name="min_amount" class="form-control"
            value="{{ old('min_amount', $asset->min_amount ?? '') }}" placeholder="e.g. 500">
        <small class="form-text text-muted">Required when amount type is "ranged".</small>
    </div>
    <div class="form-group col-md-4">
        <label>Maximum Investment (P)</label>
        <input type="number" step="0.01" name="max_amount" class="form-control"
            value="{{ old('max_amount', $asset->max_amount ?? '') }}" placeholder="e.g. 50000">
    </div>
    <div class="form-group col-md-4">
        <label>Fixed Amount (P)</label>
        <input type="number" step="0.01" name="fixed_amount" class="form-control"
            value="{{ old('fixed_amount', $asset->fixed_amount ?? '') }}" placeholder="only for fixed type">
        <small class="form-text text-muted">Required when amount type is "fixed".</small>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label>Return Type <span class="text-danger">*</span></label>
        <select name="return_type" class="form-control" required>
            <option value="percentage" {{ old('return_type', $asset->return_type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage profit</option>
            <option value="fixed" {{ old('return_type', $asset->return_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed return amount</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Return Percentage (%)</label>
        <input type="number" step="0.01" name="return_percentage" class="form-control"
            value="{{ old('return_percentage', $asset->return_percentage ?? '') }}" placeholder="e.g. 12">
        <small class="form-text text-muted">Required when return type is "percentage".</small>
    </div>
    <div class="form-group col-md-4">
        <label>Fixed Return (P)</label>
        <input type="number" step="0.01" name="fixed_return" class="form-control"
            value="{{ old('fixed_return', $asset->fixed_return ?? '') }}" placeholder="only for fixed return">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>Duration <span class="text-danger">*</span></label>
        <input type="number" name="duration" class="form-control" required
            value="{{ old('duration', $asset->duration ?? '') }}" placeholder="e.g. 90">
    </div>
    <div class="form-group col-md-3">
        <label>Duration Unit <span class="text-danger">*</span></label>
        <select name="duration_type" class="form-control" required>
            @foreach (['days','weeks','months','years'] as $u)
                <option value="{{ $u }}" {{ old('duration_type', $asset->duration_type ?? 'days') === $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Payout Frequency <span class="text-danger">*</span></label>
        <select name="payout_interval" class="form-control" required>
            @foreach (['daily','weekly','monthly'] as $p)
                <option value="{{ $p }}" {{ old('payout_interval', $asset->payout_interval ?? 'monthly') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Capacity Cap (P)</label>
        <input type="number" step="0.01" name="capacity_amount" class="form-control"
            value="{{ old('capacity_amount', $asset->capacity_amount ?? '') }}" placeholder="optional total cap">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Offer Opens (optional)</label>
        <input type="datetime-local" name="offer_starts_at" class="form-control"
            value="{{ old('offer_starts_at', optional($asset->offer_starts_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="form-group col-md-6">
        <label>Offer Closes (optional)</label>
        <input type="datetime-local" name="offer_ends_at" class="form-control"
            value="{{ old('offer_ends_at', optional($asset->offer_ends_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>
</div>

<hr>

<div class="form-group">
    <label>Government Certificate(s)</label>
    <input type="file" name="certificates[]" class="form-control-file" multiple
        accept=".pdf,.jpg,.jpeg,.png">
    <small class="form-text text-muted">PDF, JPG or PNG. Max 10&nbsp;MB each. You may select multiple files.</small>
</div>

<div class="form-group">
    <label>Supporting Document(s)</label>
    <input type="file" name="documents[]" class="form-control-file" multiple
        accept=".pdf,.jpg,.jpeg,.png">
    <small class="form-text text-muted">PDF, JPG or PNG. Max 10&nbsp;MB each. You may select multiple files.</small>
</div>
