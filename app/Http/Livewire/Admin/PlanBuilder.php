<?php

namespace App\Http\Livewire\Admin;

use App\Models\Asset;
use App\Models\Plan;
use App\Services\ReturnCalculator;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * Phase 3 — conditional investment-plan builder.
 *
 * The amount_type toggle shows EITHER fixed_amount OR min/max; the return_type
 * toggle shows EITHER fixed_return OR return_percentage. A live preview runs
 * the shared ReturnCalculator so the admin sees the resulting return as they
 * type.
 */
class PlanBuilder extends Component
{
    public ?int $planId = null;

    // Core
    public $name = '';
    public $description = '';
    public $asset_id = '';
    public $duration = '';
    public $duration_type = 'days';
    public $active = true;

    // Amount
    public $amount_type = 'fixed';
    public $fixed_amount = '';
    public $min_amount = '';
    public $max_amount = '';

    // Return
    public $return_type = 'percentage';
    public $fixed_return = '';
    public $return_percentage = '';

    // Preview-only invested amount (for ranged plans)
    public $preview_amount = '';

    public function mount($planId = null): void
    {
        if ($planId) {
            $plan = Plan::findOrFail($planId);
            $this->planId = $plan->id;
            $this->name = $plan->name;
            $this->description = $plan->description;
            $this->asset_id = $plan->asset_id;
            $this->duration = $plan->duration;
            $this->duration_type = $plan->duration_type ?: 'days';
            $this->active = (bool) $plan->active;
            $this->amount_type = $plan->amount_type ?: 'fixed';
            $this->fixed_amount = $plan->fixed_amount;
            $this->min_amount = $plan->min_amount;
            $this->max_amount = $plan->max_amount;
            $this->return_type = $plan->return_type === 'percentage' ? 'percentage' : 'fixed';
            $this->fixed_return = $plan->fixed_return;
            $this->return_percentage = $plan->return_percentage;
        }
    }

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'asset_id'          => 'required|exists:assets,id',
            'duration'          => 'nullable|numeric|min:1',
            'duration_type'     => 'required|in:days,weeks,months,years',
            'active'            => 'boolean',

            'amount_type'       => 'required|in:fixed,ranged',
            'fixed_amount'      => 'required_if:amount_type,fixed|nullable|numeric|min:0',
            'min_amount'        => 'required_if:amount_type,ranged|nullable|numeric|min:0',
            'max_amount'        => 'required_if:amount_type,ranged|nullable|numeric|gte:min_amount',

            'return_type'       => 'required|in:fixed,percentage',
            'fixed_return'      => 'required_if:return_type,fixed|nullable|numeric|min:0',
            'return_percentage' => 'required_if:return_type,percentage|nullable|numeric|min:0|max:1000',
        ];
    }

    /**
     * Live return preview using the single ReturnCalculator. Returns null when
     * the form isn't complete enough to compute (so the UI shows a dash).
     */
    public function getPreviewProperty(): ?array
    {
        $plan = new Plan([
            'amount_type'       => $this->amount_type,
            'fixed_amount'      => $this->numeric($this->fixed_amount),
            'min_amount'        => $this->numeric($this->min_amount),
            'max_amount'        => $this->numeric($this->max_amount),
            'return_type'       => $this->return_type,
            'fixed_return'      => $this->numeric($this->fixed_return),
            'return_percentage' => $this->numeric($this->return_percentage),
        ]);

        $amount = $this->amount_type === 'ranged'
            ? $this->numeric($this->preview_amount ?: $this->min_amount)
            : null;

        try {
            return app(ReturnCalculator::class)->calculate($plan, $amount);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function save()
    {
        $data = $this->validate();

        $plan = $this->planId ? Plan::findOrFail($this->planId) : new Plan();

        $plan->name = $data['name'];
        $plan->slug = Str::slug($data['name']) . ($this->planId ? '' : '-' . Str::random(5));
        $plan->description = $data['description'] ?? null;
        $plan->asset_id = $data['asset_id'];
        $plan->duration = $data['duration'] ?: null;
        $plan->duration_type = $data['duration_type'];
        $plan->active = (bool) $this->active;

        $plan->amount_type = $data['amount_type'];
        $plan->fixed_amount = $data['amount_type'] === 'fixed' ? $data['fixed_amount'] : null;
        $plan->min_amount = $data['amount_type'] === 'ranged' ? $data['min_amount'] : null;
        $plan->max_amount = $data['amount_type'] === 'ranged' ? $data['max_amount'] : null;

        $plan->return_type = $data['return_type'];
        $plan->fixed_return = $data['return_type'] === 'fixed' ? $data['fixed_return'] : null;
        $plan->return_percentage = $data['return_type'] === 'percentage' ? $data['return_percentage'] : null;

        if (!$this->planId) {
            $plan->sort_order = (int) Plan::max('sort_order') + 1;
        }

        $plan->save();

        \App\Models\AuditLog::record(
            $this->planId ? 'plan.updated' : 'plan.created',
            $plan,
            ($this->planId ? 'Updated' : 'Created') . " investment plan \"{$plan->name}\"",
            ['amount_type' => $plan->amount_type, 'return_type' => $plan->return_type, 'asset_id' => $plan->asset_id]
        );

        session()->flash('success', $this->planId ? 'Investment plan updated.' : 'Investment plan created.');

        return redirect()->route('admin.investment-plans.index');
    }

    private function numeric($value): ?float
    {
        return ($value === '' || $value === null) ? null : (float) $value;
    }

    public function render()
    {
        return view('livewire.admin.plan-builder', [
            'assets' => Asset::active()->orderBy('name')->get(),
        ]);
    }
}
