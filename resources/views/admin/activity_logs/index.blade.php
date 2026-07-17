@extends('admin.layouts.app')
@section('title', 'Activity Logs')

@section('content')
<div >

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary">Activity Logs</h1>
            <p class="text-sm text-secondary-500">Track all user actions across the system</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-md bg-accent-50 text-primary border border-primary-200 text-sm font-medium">
            <i class="fas fa-list-alt mr-2"></i> {{ $activities->total() }} Records
        </span>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white border border-secondary-200 rounded-xl p-4 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">

            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search user, action, model, IP..."
                    class="w-full h-10 px-4 border border-secondary-300 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <select name="action" class="w-full h-10 px-3 border border-secondary-300 rounded-lg text-sm focus:ring-2 focus:ring-primary">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" @selected(request('action')===$action)>
                        {{ ucfirst($action) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="model_type" class="w-full h-10 px-3 border border-secondary-300 rounded-lg text-sm focus:ring-2 focus:ring-primary">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type)
                    <option value="{{ $type }}" @selected(request('model_type')===$type)>
                        {{ class_basename($type) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 h-10 px-4 bg-primary text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium flex items-center justify-center">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}"
                    class="h-10 px-3 border border-secondary-300 text-secondary-600 rounded-lg hover:bg-secondary-50 transition flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </a>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-secondary-500 whitespace-nowrap">From:</span>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="flex-1 h-10 px-3 border border-secondary-300 rounded-lg text-sm focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-secondary-500 whitespace-nowrap">To:</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="flex-1 h-10 px-3 border border-secondary-300 rounded-lg text-sm focus:ring-2 focus:ring-primary">
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white border border-secondary-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">User</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">Action</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">Model</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">Description</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">IP Address</th>
                        <th class="px-4 py-3 text-left font-semibold text-secondary-600">Date</th>
                        <th class="px-4 py-3 text-center font-semibold text-secondary-600">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $log)
                    <tr class="hover:bg-secondary-50 transition">
                        <td class="px-4 py-3 text-secondary-400 font-mono text-xs">{{ $log->id }}</td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-accent-100 text-primary flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-medium text-secondary-800">{{ $log->user->name ?? '<em class="text-secondary-400">Deleted</em>' }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            @php
                            $actionColors = [
                            'created' => 'bg-success-100 text-success',
                            'updated' => 'bg-primary-100 text-primary',
                            'deleted' => 'bg-danger-100 text-danger',
                            'restored' => 'bg-warning-100 text-warning',
                            'login' => 'bg-accent-100 text-primary',
                            'logout' => 'bg-secondary-100 text-secondary-700',
                            ];
                            $color = $actionColors[$log->action] ?? 'bg-accent-100 text-accent';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @if($log->model_type)
                            <span class="text-secondary-700 font-medium">{{ class_basename($log->model_type) }}</span>
                            @if($log->model_id)
                            <span class="text-secondary-400 text-xs ml-1">#{{ $log->model_id }}</span>
                            @endif
                            @else
                            <span class="text-secondary-400">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-secondary-600 max-w-xs truncate" title="{{ $log->description }}">
                            {{ $log->description ?? '—' }}
                        </td>

                        <td class="px-4 py-3 font-mono text-xs text-secondary-500">
                            {{ $log->ip_address ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-secondary-500 text-xs whitespace-nowrap">
                            <div>{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-secondary-400">{{ $log->created_at->format('h:i A') }}</div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <button onclick="openOffcanvas(this)"
                                data-log="{{ json_encode([
                                    'id'         => $log->id,
                                    'user'       => $log->user->name ?? null,
                                    'action'     => $log->action,
                                    'model_type' => $log->model_type ? class_basename($log->model_type) : null,
                                    'model_id'   => $log->model_id,
                                    'description'=> $log->description,
                                    'ip_address' => $log->ip_address,
                                    'address'    => $log->address,
                                    'user_agent' => $log->user_agent,
                                    'old_values' => $log->old_values,
                                    'new_values' => $log->new_values,
                                    'created_at' => $log->created_at->format('M d, Y  h:i A'),
                                ]) }}"
                                class="inline-flex items-center px-2.5 py-1 rounded-lg bg-secondary-100 text-secondary-600 hover:bg-accent-100 hover:text-primary transition text-xs font-medium">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center text-secondary-400">
                                <i class="fas fa-clipboard-list text-4xl mb-3"></i>
                                <p class="font-medium">No activity logs found</p>
                                <p class="text-sm mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ── Offcanvas Backdrop ── --}}
<div id="ocBackdrop"
    onclick="closeOffcanvas()"
    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm hidden transition-opacity duration-300">
</div>

{{-- ── Offcanvas Panel ── --}}
<div id="offcanvas"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col
           translate-x-full transition-transform duration-300 ease-in-out">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-accent-100 text-primary flex items-center justify-center">
                <i class="fas fa-history text-sm"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-secondary-800">Log Details</h3>
                <p id="oc-id" class="text-xs text-secondary-400">#—</p>
            </div>
        </div>
        <button onclick="closeOffcanvas()"
            class="h-8 w-8 rounded-lg text-secondary-400 hover:text-secondary-700 hover:bg-secondary-100 transition flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Scrollable Body --}}
    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">

        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-secondary-50 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-1">User</p>
                <p id="oc-user" class="text-sm font-semibold text-secondary-800">—</p>
            </div>
            <div class="bg-secondary-50 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-1">Action</p>
                <p id="oc-action" class="text-sm font-semibold">—</p>
            </div>
            <div class="bg-secondary-50 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-1">Model</p>
                <p id="oc-model" class="text-sm font-semibold text-secondary-800">—</p>
            </div>
            <div class="bg-secondary-50 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-1">Date</p>
                <p id="oc-date" class="text-sm font-semibold text-secondary-800">—</p>
            </div>
        </div>

        {{-- Description --}}
        <div id="oc-desc-wrap" class="hidden">
            <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-2">Description</p>
            <p id="oc-desc" class="text-sm text-secondary-700 bg-secondary-50 rounded-xl px-4 py-3 leading-relaxed"></p>
        </div>

        {{-- Network --}}
        <div>
            <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-2">Network</p>
            <div class="bg-secondary-50 rounded-xl divide-y divide-gray-100">
                <div class="flex items-start gap-3 px-4 py-3">
                    <i class="fas fa-network-wired text-secondary-400 mt-0.5 w-4 text-center"></i>
                    <div class="min-w-0">
                        <p class="text-[10px] text-secondary-400 font-medium uppercase">IP Address</p>
                        <p id="oc-ip" class="text-sm text-secondary-700 font-mono">—</p>
                    </div>
                </div>
                <div id="oc-address-row" class="flex items-start gap-3 px-4 py-3 hidden">
                    <i class="fas fa-map-marker-alt text-secondary-400 mt-0.5 w-4 text-center"></i>
                    <div class="min-w-0">
                        <p class="text-[10px] text-secondary-400 font-medium uppercase">Address</p>
                        <p id="oc-address" class="text-sm text-secondary-700 break-words"></p>
                    </div>
                </div>
                <div id="oc-ua-row" class="flex items-start gap-3 px-4 py-3 hidden">
                    <i class="fas fa-laptop text-secondary-400 mt-0.5 w-4 text-center"></i>
                    <div class="min-w-0">
                        <p class="text-[10px] text-secondary-400 font-medium uppercase">User Agent</p>
                        <p id="oc-ua" class="text-xs text-secondary-500 break-all leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Old Values --}}
        <div id="oc-old-wrap" class="hidden">
            <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-2">
                <i class="fas fa-minus-circle text-danger mr-1"></i> Old Values
            </p>
            <div class="rounded-xl border border-danger-100 overflow-hidden">
                <table class="min-w-full text-sm">
                    <tbody id="oc-old-body" class="divide-y divide-danger-50"></tbody>
                </table>
            </div>
        </div>

        {{-- New Values --}}
        <div id="oc-new-wrap" class="hidden">
            <p class="text-[10px] font-semibold text-secondary-400 uppercase tracking-wider mb-2">
                <i class="fas fa-plus-circle text-success mr-1"></i> New Values
            </p>
            <div class="rounded-xl border border-success-100 overflow-hidden">
                <table class="min-w-full text-sm">
                    <tbody id="oc-new-body" class="divide-y divide-success-50"></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    const actionColors = {
        created: 'bg-success-100 text-success',
        updated: 'bg-primary-100 text-primary',
        deleted: 'bg-danger-100 text-danger',
        restored: 'bg-warning-100 text-warning',
        login: 'bg-accent-100 text-primary',
        logout: 'bg-secondary-100 text-secondary-600',
    };

    function openOffcanvas(btn) {
        const log = JSON.parse(btn.dataset.log);

        document.getElementById('oc-id').textContent = '#' + log.id;
        document.getElementById('oc-user').textContent = log.user ?? 'Deleted User';
        document.getElementById('oc-ip').textContent = log.ip_address ?? '—';
        document.getElementById('oc-date').textContent = log.created_at ?? '—';

        // Action badge
        const color = actionColors[log.action] ?? 'bg-accent-100 text-accent';
        document.getElementById('oc-action').innerHTML =
            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${color}">${cap(log.action)}</span>`;

        // Model
        document.getElementById('oc-model').textContent =
            log.model_type ? (log.model_type + (log.model_id ? ' #' + log.model_id : '')) : '—';

        // Description
        const descWrap = document.getElementById('oc-desc-wrap');
        if (log.description) {
            document.getElementById('oc-desc').textContent = log.description;
            descWrap.classList.remove('hidden');
        } else {
            descWrap.classList.add('hidden');
        }

        // Address
        toggleRow('oc-address-row', 'oc-address', log.address);

        // User Agent
        toggleRow('oc-ua-row', 'oc-ua', log.user_agent);

        // Old Values
        fillValuesTable('oc-old-wrap', 'oc-old-body', log.old_values, 'bg-danger-50', 'text-danger');

        // New Values
        fillValuesTable('oc-new-wrap', 'oc-new-body', log.new_values, 'bg-success-50', 'text-success');

        document.getElementById('ocBackdrop').classList.remove('hidden');
        requestAnimationFrame(() => {
            document.getElementById('offcanvas').classList.remove('translate-x-full');
        });
    }

    function closeOffcanvas() {
        document.getElementById('offcanvas').classList.add('translate-x-full');
        document.getElementById('ocBackdrop').classList.add('hidden');
    }

    function toggleRow(rowId, textId, value) {
        const row = document.getElementById(rowId);
        if (value) {
            document.getElementById(textId).textContent = value;
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    }

    function fillValuesTable(wrapId, bodyId, values, rowBg, textColor) {
        const wrap = document.getElementById(wrapId);
        const body = document.getElementById(bodyId);
        body.innerHTML = '';
        if (values && Object.keys(values).length) {
            Object.entries(values).forEach(([key, val]) => {
                const display = (val === null || val === undefined) ? '<em class="text-secondary-400">null</em>' :
                    (typeof val === 'object' ? `<code class="text-xs">${JSON.stringify(val)}</code>` : val);
                body.insertAdjacentHTML('beforeend',
                    `<tr class="${rowBg}">
                        <td class="px-4 py-2 font-medium text-secondary-600 w-1/3 align-top">${key}</td>
                        <td class="px-4 py-2 ${textColor} break-all align-top">${display}</td>
                    </tr>`);
            });
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
        }
    }

    function cap(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeOffcanvas();
    });
</script>
@endsection