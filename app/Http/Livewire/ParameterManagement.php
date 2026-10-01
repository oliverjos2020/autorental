<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SystemParameter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class ParameterManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /* ─── Gate / Auth ──────────────────────────────────────────── */
    public bool   $authenticated  = false;
    public string $adminPassword  = '';
    public string $authError      = '';

    /* ─── List controls ─────────────────────────────────────────── */
    public string $search     = '';
    public string $filterGroup = '';
    public string $limit      = '10';

    /* ─── Create form ─────────────────────────────────────────── */
    public string $param_key   = '';
    public string $param_value = '';
    public string $param_group = 'General';
    public string $description = '';
    public bool   $is_secret   = false;
    public bool   $is_active   = true;

    /* ─── Edit form ─────────────────────────────────────────────── */
    public ?int   $editingID          = null;
    public string $editingKey         = '';
    public string $editingValue       = '';
    public string $editingGroup       = '';
    public string $editingDescription = '';
    public bool   $editingIsSecret    = false;
    public bool   $editingIsActive    = true;

    /* ─── UI toggles ─────────────────────────────────────────────── */
    public bool   $showCreateForm = false;
    public array  $revealedValues = [];   // IDs whose secret values are shown

    protected $queryString = ['limit', 'search', 'filterGroup'];

    /* ─── Predefined groups ──────────────────────────────────────── */
    public array $groups = [
        'General',
        'Payment Gateway',
        'SMS / OTP',
        'Email',
        'Push Notification',
        'Maps / Location',
        'Security',
        'Other',
    ];

    /* ═══════════════ AUTH ═══════════════════════════════════════ */

    public function authenticate(): void
    {
        if (!Hash::check($this->adminPassword, Auth::user()->password)) {
            $this->authError = 'Incorrect password. Access denied.';
            return;
        }
        $this->authenticated = true;
        $this->authError     = '';
        $this->adminPassword = '';
    }

    public function lockPage(): void
    {
        $this->authenticated  = false;
        $this->revealedValues = [];
    }

    /* ═══════════════ SEARCH / PAGINATION ═══════════════════════ */

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingLimit(): void   { $this->resetPage(); }
    public function updatingFilterGroup(): void { $this->resetPage(); }

    /* ═══════════════ CREATE ═════════════════════════════════════ */

    public function toggleCreateForm(): void
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->reset(['param_key','param_value','param_group','description','is_secret','is_active']);
        $this->param_group = 'General';
        $this->is_active   = true;
    }
 
    public function create(): void
    {
        $this->validate([
            'param_key'   => ['required', 'string', 'max:100', 'unique:system_parameters,param_key', 'regex:/^[A-Z0-9_]+$/'],
            'param_value' => ['nullable', 'string'],
            'param_group' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'param_key.regex' => 'Key must be uppercase letters, digits, and underscores only (e.g. PAYSTACK_SECRET_KEY).',
            'param_key.unique' => 'This key already exists.',
        ]);

        try {
            SystemParameter::create([
                'param_key'   => strtoupper(trim($this->param_key)),
                'param_value' => $this->param_value,
                'param_group' => $this->param_group,
                'description' => $this->description,
                'is_secret'   => $this->is_secret,
                'is_active'   => $this->is_active,
            ]);

            $this->showCreateForm = false;
            $this->reset(['param_key','param_value','param_group','description','is_secret','is_active']);
            $this->param_group = 'General';
            $this->is_active   = true;

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Parameter created successfully.']);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /* ═══════════════ EDIT ═══════════════════════════════════════ */

    public function edit(int $id): void
    {
        $param = SystemParameter::findOrFail($id);
        $this->editingID          = $id;
        $this->editingKey         = $param->param_key;
        $this->editingValue       = $param->param_value ?? '';
        $this->editingGroup       = $param->param_group;
        $this->editingDescription = $param->description ?? '';
        $this->editingIsSecret    = (bool) $param->is_secret;
        $this->editingIsActive    = (bool) $param->is_active;

        // Close create form if open
        $this->showCreateForm = false;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingID','editingKey','editingValue','editingGroup','editingDescription','editingIsSecret','editingIsActive']);
    }

    public function update(): void
    {
        $this->validate([
            'editingValue'       => ['nullable', 'string'],
            'editingGroup'       => ['required', 'string', 'max:60'],
            'editingDescription' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            SystemParameter::findOrFail($this->editingID)->update([
                'param_value' => $this->editingValue,
                'param_group' => $this->editingGroup,
                'description' => $this->editingDescription,
                'is_secret'   => $this->editingIsSecret,
                'is_active'   => $this->editingIsActive,
            ]);

            $this->cancelEdit();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Parameter updated successfully.']);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /* ═══════════════ DELETE ═════════════════════════════════════ */

    public function delete(int $id): void
    {
        try {
            SystemParameter::findOrFail($id)->delete();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Parameter deleted.']);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /* ═══════════════ SECRET REVEAL ══════════════════════════════ */

    public function toggleReveal(int $id): void
    {
        if (in_array($id, $this->revealedValues)) {
            $this->revealedValues = array_values(array_filter($this->revealedValues, fn($v) => $v !== $id));
        } else {
            $this->revealedValues[] = $id;
        }
    }

    /* ═══════════════ RENDER ═════════════════════════════════════ */

    public function render()
    {
        $query = SystemParameter::query()
            ->where(function ($q) {
                $q->where('param_key', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });

        if ($this->filterGroup) {
            $query->where('param_group', $this->filterGroup);
        }

        $parameters = $query->orderBy('param_group')->orderBy('param_key')->paginate((int) $this->limit);

        // All distinct groups already in DB (for filter dropdown)
        $dbGroups = SystemParameter::select('param_group')->distinct()->orderBy('param_group')->pluck('param_group')->toArray();

        return view('livewire.parameter-management', [
            'parameters' => $parameters,
            'dbGroups'   => $dbGroups,
        ])->layout('components.dashboard.dashboard-master');
    }
}
