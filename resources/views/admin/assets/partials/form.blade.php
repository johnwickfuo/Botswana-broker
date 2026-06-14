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
