<div>
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left: form --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <label>Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="name"
                                placeholder="e.g. Morupule Coal — Growth">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Asset <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model.defer="asset_id">
                                <option value="">— Select asset —</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->category }})</option>
                                @endforeach
                            </select>
                            @error('asset_id') <small class="text-danger">{{ $message }}</small> @enderror
                            @if ($assets->isEmpty())
                                <small class="text-warning d-block">No active assets yet — create one under Assets first.</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" wire:model.defer="description"></textarea>
                        </div>

                        <hr>

                        {{-- Amount type toggle --}}
                        <div class="form-group">
                            <label>Investment Amount Type <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="amount_type">
                                <option value="fixed">Fixed amount</option>
                                <option value="ranged">Ranged (min–max)</option>
                            </select>
                        </div>

                        @if ($amount_type === 'fixed')
                            <div class="form-group">
                                <label>Fixed Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" wire:model="fixed_amount">
                                @error('fixed_amount') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @else
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Minimum Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" wire:model="min_amount">
                                    @error('min_amount') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Maximum Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" wire:model="max_amount">
                                    @error('max_amount') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        @endif

                        <hr>

                        {{-- Return type toggle --}}
                        <div class="form-group">
                            <label>Return Type <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="return_type">
                                <option value="percentage">Percentage of investment</option>
                                <option value="fixed">Fixed return amount</option>
                            </select>
                        </div>

                        @if ($return_type === 'percentage')
                            <div class="form-group">
                                <label>Return Percentage (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" wire:model="return_percentage">
                                @error('return_percentage') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @else
                            <div class="form-group">
                                <label>Fixed Return Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" wire:model="fixed_return">
                                @error('fixed_return') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @endif

                        <hr>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Duration</label>
                                <input type="number" class="form-control" wire:model.defer="duration">
                                @error('duration') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Duration Unit</label>
                                <select class="form-control" wire:model.defer="duration_type">
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="activeSwitch"
                                    wire:model.defer="active">
                                <label class="custom-control-label" for="activeSwitch">Active (available to citizens)</label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ $planId ? 'Update Plan' : 'Create Plan' }}
                            </button>
                            <a href="{{ route('admin.investment-plans.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: live preview --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Return Preview</h4></div>
                    <div class="card-body">
                        @if ($amount_type === 'ranged')
                            <div class="form-group">
                                <label>Sample investment amount</label>
                                <input type="number" step="0.01" class="form-control" wire:model="preview_amount"
                                    placeholder="within min–max">
                            </div>
                        @endif

                        @php($preview = $this->preview)
                        @if ($preview)
                            <table class="table table-sm mb-0">
                                <tr><td>Principal</td><td class="text-right">{{ number_format($preview['principal'], 2) }}</td></tr>
                                <tr><td>Return (profit)</td><td class="text-right text-success">{{ number_format($preview['return'], 2) }}</td></tr>
                                <tr class="font-weight-bold"><td>Total payout</td><td class="text-right">{{ number_format($preview['total'], 2) }}</td></tr>
                            </table>
                        @else
                            <p class="text-muted mb-0">Fill in the amount and return fields
                                @if ($amount_type === 'ranged') (and a sample amount within range) @endif
                                to preview the calculated return.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
