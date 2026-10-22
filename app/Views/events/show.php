<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- BREADCRUMB & TOP ACTIONS -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <nav class="flex text-xs font-semibold text-slate-500 gap-2 items-center">
        <a href="<?= site_url('events') ?>" class="hover:text-indigo-600">Events</a>
        <span>/</span>
        <span class="text-slate-800 truncate max-w-[200px] sm:max-w-none"><?= esc($event['title']) ?></span>
    </nav>
    <div class="flex items-center gap-2">
        <a href="<?= site_url('events/' . $event['id'] . '/edit') ?>"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-2xs transition">
            <i class="bi bi-pencil"></i> Edit Details
        </a>
        <button type="button" onclick="openModal('modal-assign-provider')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-2xs transition">
            <i class="bi bi-person-plus-fill"></i> Assign Provider
        </button>
        <button type="button" onclick="openModal('modal-delete-event')"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition" title="Delete Event">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

<!-- EVENT OVERVIEW HERO CARD -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
        <div class="lg:col-span-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <?= esc($event['event_type']) ?>
                </span>

                <?php
                    $statusClasses = [
                        'planning'    => 'bg-sky-50 text-sky-700 border-sky-200',
                        'confirmed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'completed'   => 'bg-slate-100 text-slate-700 border-slate-200',
                        'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-200',
                        'draft'       => 'bg-gray-100 text-gray-700 border-gray-200',
                    ];
                    $badgeClass = $statusClasses[$event['status']] ?? 'bg-slate-100 text-slate-700';

                    $eventDate = new DateTime($event['event_date']);
                    $today = new DateTime('today');
                    $diff = (int) $today->diff($eventDate)->format('%r%a');
                ?>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $badgeClass ?> capitalize">
                    <?= esc(str_replace('_', ' ', $event['status'])) ?>
                </span>

                <?php if ($diff === 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-600 text-white">Event is Today!</span>
                <?php elseif ($diff > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">In <?= $diff ?> days</span>
                <?php else: ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-400 border border-slate-200"><?= abs($diff) ?> days ago</span>
                <?php endif; ?>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                <?= esc($event['title']) ?>
            </h1>

            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-600">
                <div class="flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-indigo-600 text-sm"></i>
                    <strong class="text-slate-900"><?= $eventDate->format('l, F j, Y') ?></strong>
                </div>
                <?php if ($event['start_time']): ?>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-clock text-indigo-600 text-sm"></i>
                        <span><?= substr($event['start_time'], 0, 5) ?><?= $event['end_time'] ? ' - ' . substr($event['end_time'], 0, 5) : '' ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-rose-500 text-sm"></i>
                    <span><?= esc($event['location_venue'] ?? 'Venue To Be Announced') ?></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-sky-500 text-sm"></i>
                    <span><?= esc($event['guest_count']) ?> Expected Guests</span>
                </div>
            </div>

            <?php if (!empty($event['description'])): ?>
                <div class="mt-4 p-3 rounded-xl bg-slate-50 border-l-4 border-indigo-600 text-xs text-slate-600 leading-relaxed">
                    <?= nl2br(esc($event['description'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- CLIENT INFO CARD -->
        <div class="lg:col-span-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 flex items-center gap-1.5">
                    <i class="bi bi-person-badge text-indigo-600"></i> Client Details
                </h4>
                <div class="font-bold text-slate-900 text-base mb-2"><?= esc($event['client_name']) ?></div>
                <div class="space-y-1.5 text-xs text-slate-600">
                    <?php if ($event['client_email']): ?>
                        <div class="flex items-center gap-2 truncate">
                            <i class="bi bi-envelope text-slate-400"></i>
                            <a href="mailto:<?= esc($event['client_email']) ?>" class="hover:text-indigo-600 text-slate-700"><?= esc($event['client_email']) ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if ($event['client_phone']): ?>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-telephone text-slate-400"></i>
                            <a href="tel:<?= esc($event['client_phone']) ?>" class="hover:text-indigo-600 text-slate-700"><?= esc($event['client_phone']) ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($event['notes'])): ?>
                    <div class="mt-3 pt-3 border-t border-slate-200 text-xs text-slate-500">
                        <strong class="text-slate-700">Notes:</strong> <?= esc($event['notes']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- FINANCIAL TRACKER & BUDGET OVERVIEW -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-4 mb-5 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <i class="bi bi-cash-stack text-emerald-600 text-lg"></i>
            <h2 class="text-base font-bold text-slate-900">Financial Tracker & Budget Variance</h2>
        </div>
        <div>
            <?php if ($event['budget_variance'] < 0): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <i class="bi bi-exclamation-triangle-fill"></i> Over Budget by $<?= number_format(abs($event['budget_variance']), 2) ?>
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="bi bi-check-circle-fill"></i> Within Budget: $<?= number_format($event['budget_variance'], 2) ?> remaining
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- 4 SUMMARY CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5 text-center">
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Target Budget</span>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1">$<?= number_format($event['budget'], 2) ?></h3>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Approved budget</span>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Committed Costs</span>
            <h3 class="text-2xl font-extrabold mt-1 <?= $event['budget_variance'] < 0 ? 'text-rose-600' : 'text-indigo-600' ?>">
                $<?= number_format($event['total_provider_cost'], 2) ?>
            </h3>
            <span class="text-[11px] text-slate-500 mt-0.5 block"><?= count($assignedProviders) ?> providers hired</span>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Deposits Paid</span>
            <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">$<?= number_format($event['total_deposit_paid'], 2) ?></h3>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Cleared payments</span>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Unpaid Balance</span>
            <h3 class="text-2xl font-extrabold text-amber-600 mt-1">$<?= number_format($event['remaining_balance'], 2) ?></h3>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Remaining vendor dues</span>
        </div>
    </div>

    <!-- PROGRESS BAR -->
    <?php
        $budgetPercent = $event['budget'] > 0 ? round(($event['total_provider_cost'] / $event['budget']) * 100) : 0;
        $paidPercent = $event['budget'] > 0 ? round(($event['total_deposit_paid'] / $event['budget']) * 100) : 0;
        $barColor = $event['budget_variance'] < 0 ? 'bg-rose-500' : 'bg-indigo-600';
    ?>
    <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5 font-medium">
        <span>Allocation: <?= $budgetPercent ?>% of target budget</span>
        <span>Deposits cleared: <?= $paidPercent ?>%</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
        <div class="<?= $barColor ?> h-2.5 rounded-full transition-all" style="width: <?= min($budgetPercent, 100) ?>%"></div>
    </div>
</div>

<!-- ASSIGNED PROVIDERS & TASKS GRID -->
<div class="grid grid-cols-1 gap-6">

    <!-- ASSIGNED PROVIDERS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-people-fill text-indigo-600 text-lg"></i>
                <h2 class="text-base font-bold text-slate-900">Assigned Service Providers</h2>
                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700">
                    <?= count($assignedProviders) ?>
                </span>
            </div>
            <button type="button" onclick="openModal('modal-assign-provider')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-2xs transition">
                <i class="bi bi-plus-lg"></i> Assign Provider
            </button>
        </div>

        <?php if (empty($assignedProviders)): ?>
            <div class="text-center py-12 px-4">
                <i class="bi bi-people text-4xl text-slate-300 block mb-2"></i>
                <p class="text-sm font-medium text-slate-600 mb-3">No vendors assigned to this event yet.</p>
                <button type="button" onclick="openModal('modal-assign-provider')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-indigo-600 text-white rounded-lg">
                    Assign Catering, Venue, DJ, etc.
                </button>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5">Category & Provider</th>
                            <th class="px-4 py-3.5">Service Description</th>
                            <th class="px-4 py-3.5">Agreed Fee</th>
                            <th class="px-4 py-3.5">Deposit Paid</th>
                            <th class="px-4 py-3.5">Balance</th>
                            <th class="px-4 py-3.5">Payment</th>
                            <th class="px-4 py-3.5">Contract</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($assignedProviders as $ep): ?>
                            <?php
                                $balance = (float)$ep['agreed_cost'] - (float)$ep['deposit_paid'];
                                $payBadge = [
                                    'paid'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'pending' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $payClass = $payBadge[$ep['payment_status']] ?? 'bg-slate-100 text-slate-700';
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shadow-2xs">
                                            <i class="bi <?= esc($ep['category_icon'] ?? 'bi-tag') ?>"></i>
                                        </div>
                                        <div>
                                            <a href="<?= site_url('providers/' . $ep['provider_id']) ?>" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                                <?= esc($ep['provider_name']) ?>
                                            </a>
                                            <span class="text-xs text-slate-400"><?= esc($ep['category_name'] ?? 'General') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-xs font-medium text-slate-800"><?= esc($ep['service_description'] ?? 'Event Service') ?></div>
                                    <?php if (!empty($ep['notes'])): ?>
                                        <div class="text-[11px] text-slate-400 truncate max-w-[200px]" title="<?= esc($ep['notes']) ?>">
                                            <?= esc($ep['notes']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap font-bold text-slate-900">
                                    $<?= number_format($ep['agreed_cost'], 2) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap font-semibold text-emerald-600">
                                    $<?= number_format($ep['deposit_paid'], 2) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap font-semibold <?= $balance > 0 ? 'text-amber-600' : 'text-slate-400' ?>">
                                    $<?= number_format($balance, 2) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border <?= $payClass ?> capitalize">
                                        <?= esc($ep['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 capitalize">
                                        <i class="bi bi-file-earmark-text text-[10px]"></i> <?= esc($ep['contract_status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick="openModal('modal-edit-prov-<?= $ep['id'] ?>')"
                                                class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Assignment">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" onclick="openModal('modal-remove-prov-<?= $ep['id'] ?>')"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Remove Assignment">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <!-- EDIT ASSIGNMENT MODAL -->
                                    <div id="modal-edit-prov-<?= $ep['id'] ?>" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
                                            <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers/' . $ep['id']) ?>">
                                                <?= csrf_field() ?>
                                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                                    <h3 class="text-base font-bold text-slate-900">Edit Assignment: <?= esc($ep['provider_name']) ?></h3>
                                                    <button type="button" onclick="closeModal('modal-edit-prov-<?= $ep['id'] ?>')" class="text-slate-400 hover:text-slate-600">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>

                                                <div class="space-y-4 text-sm">
                                                    <div>
                                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Service Description</label>
                                                        <input type="text" name="service_description" value="<?= esc($ep['service_description']) ?>"
                                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Agreed Fee ($)</label>
                                                            <input type="number" step="0.01" min="0" name="agreed_cost" value="<?= esc($ep['agreed_cost']) ?>"
                                                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Deposit Paid ($)</label>
                                                            <input type="number" step="0.01" min="0" name="deposit_paid" value="<?= esc($ep['deposit_paid']) ?>"
                                                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Payment Status</label>
                                                            <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                                                                <option value="pending" <?= $ep['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                <option value="partial" <?= $ep['payment_status'] === 'partial' ? 'selected' : '' ?>>Partial</option>
                                                                <option value="paid" <?= $ep['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid in Full</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Contract Status</label>
                                                            <select name="contract_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                                                                <option value="draft" <?= $ep['contract_status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                                                <option value="sent" <?= $ep['contract_status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                                                                <option value="signed" <?= $ep['contract_status'] === 'signed' ? 'selected' : '' ?>>Signed</option>
                                                                <option value="completed" <?= $ep['contract_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes / Terms</label>
                                                        <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition"><?= esc($ep['notes']) ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                                                    <button type="button" onclick="closeModal('modal-edit-prov-<?= $ep['id'] ?>')"
                                                            class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- REMOVE ASSIGNMENT MODAL -->
                                    <div id="modal-remove-prov-<?= $ep['id'] ?>" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                                                    <i class="bi bi-person-x-fill"></i>
                                                </div>
                                                <button type="button" onclick="closeModal('modal-remove-prov-<?= $ep['id'] ?>')" class="text-slate-400 hover:text-slate-600">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900">Remove Provider?</h3>
                                            <p class="text-sm text-slate-500 mt-2">
                                                Are you sure you want to unassign <strong><?= esc($ep['provider_name']) ?></strong> from this event?
                                            </p>
                                            <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                                                <button type="button" onclick="closeModal('modal-remove-prov-<?= $ep['id'] ?>')"
                                                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                                    Cancel
                                                </button>
                                                <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers/' . $ep['id'] . '/delete') ?>">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition">
                                                        Yes, Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- EVENT CHECKLIST & TASKS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="bi bi-check2-circle text-emerald-600 text-lg"></i>
                <h2 class="text-base font-bold text-slate-900">Checklist & Milestone Tasks</h2>
                <span id="task-counter-badge" class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700">
                    <?= $event['completed_tasks'] ?> / <?= $event['total_tasks'] ?> Done (<?= $event['task_percentage'] ?>%)
                </span>
            </div>
            <button type="button" onclick="openModal('modal-add-task')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-2xs transition">
                <i class="bi bi-plus-lg"></i> Add Task
            </button>
        </div>

        <?php if ($event['total_tasks'] > 0): ?>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-5 overflow-hidden">
                <div id="task-progress-bar" class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: <?= $event['task_percentage'] ?>%"></div>
            </div>
        <?php endif; ?>

        <?php if (empty($tasks)): ?>
            <div class="text-center py-8">
                <i class="bi bi-card-checklist text-3xl text-slate-300 block mb-2"></i>
                <p class="text-sm font-medium text-slate-500 mb-2">No tasks added to the checklist yet.</p>
                <button type="button" onclick="openModal('modal-add-task')"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    Add first milestone &rarr;
                </button>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($tasks as $task): ?>
                    <div class="py-3 flex items-center justify-between gap-4 group">
                        <div class="flex items-start gap-3">
                            <input type="checkbox"
                                   onchange="toggleTaskStatus(<?= $task['id'] ?>, this)"
                                   <?= $task['status'] === 'completed' ? 'checked' : '' ?>
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                            <div>
                                <span id="task-label-<?= $task['id'] ?>"
                                      class="text-sm font-medium text-slate-800 leading-snug <?= $task['status'] === 'completed' ? 'line-through text-slate-400' : '' ?>">
                                    <?= esc($task['title']) ?>
                                </span>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                    <?php if ($task['provider_name']): ?>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-[10px] font-semibold border border-slate-200">
                                            <i class="bi bi-person"></i><?= esc($task['provider_name']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($task['due_date']): ?>
                                        <span><i class="bi bi-calendar-event mr-1 text-slate-400"></i>Due <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                    <?php endif; ?>
                                    <?php if ($task['priority'] === 'high'): ?>
                                        <span class="px-1.5 py-0.2 rounded bg-rose-50 text-rose-600 font-bold text-[10px]">High</span>
                                    <?php elseif ($task['priority'] === 'medium'): ?>
                                        <span class="px-1.5 py-0.2 rounded bg-amber-50 text-amber-700 font-semibold text-[10px]">Medium</span>
                                    <?php else: ?>
                                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-500 text-[10px]">Low</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <form method="post" action="<?= site_url('events/' . $event['id'] . '/tasks/' . $task['id'] . '/delete') ?>" onsubmit="return confirm('Delete this task?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="p-1 text-slate-300 hover:text-rose-600 transition" title="Delete Task">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL: ASSIGN PROVIDER -->
<div id="modal-assign-provider" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
        <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers') ?>">
            <?= csrf_field() ?>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Assign Provider to Event</h3>
                <button type="button" onclick="closeModal('modal-assign-provider')" class="text-slate-400 hover:text-slate-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Select Provider <span class="text-rose-500">*</span>
                    </label>
                    <select name="provider_id" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                        <option value="">-- Choose a Provider --</option>
                        <?php foreach ($allProviders as $prov): ?>
                            <option value="<?= $prov['id'] ?>">
                                <?= esc($prov['name']) ?> (<?= esc($prov['category_name'] ?? 'General') ?>) - <?= esc($prov['price_range']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Need a new vendor? <a href="<?= site_url('providers/new') ?>" target="_blank" class="text-indigo-600 font-semibold">Register in directory</a></p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Service Description / Scope</label>
                    <input type="text" name="service_description" placeholder="e.g. 5-hour DJ set & sound system"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Agreed Fee / Cost ($)</label>
                        <input type="number" step="0.01" min="0" name="agreed_cost" placeholder="1500.00" value="0.00"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Deposit Paid ($)</label>
                        <input type="number" step="0.01" min="0" name="deposit_paid" placeholder="500.00" value="0.00"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial Deposit</option>
                            <option value="paid">Paid in Full</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Contract Status</label>
                        <select name="contract_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                            <option value="draft">Draft</option>
                            <option value="sent">Contract Sent</option>
                            <option value="signed">Contract Signed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes / Terms</label>
                    <textarea name="notes" rows="2" placeholder="Setup time, equipment requests, parking..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-assign-provider')"
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                    Assign Provider
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ADD TASK -->
<div id="modal-add-task" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
        <form method="post" action="<?= site_url('events/' . $event['id'] . '/tasks') ?>">
            <?= csrf_field() ?>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Add Milestone Task</h3>
                <button type="button" onclick="closeModal('modal-add-task')" class="text-slate-400 hover:text-slate-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Task Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" required placeholder="e.g. Confirm final tasting menu"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Link to Provider (Optional)</label>
                    <select name="provider_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                        <option value="">-- No specific vendor --</option>
                        <?php foreach ($assignedProviders as $ep): ?>
                            <option value="<?= $ep['provider_id'] ?>"><?= esc($ep['provider_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-add-task')"
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                    Add Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: DELETE EVENT -->
<div id="modal-delete-event" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <button type="button" onclick="closeModal('modal-delete-event')" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Delete Event?</h3>
        <p class="text-sm text-slate-500 mt-2">
            Are you sure you want to permanently delete <strong><?= esc($event['title']) ?></strong>? All assigned vendors and task records will also be removed.
        </p>
        <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
            <button type="button" onclick="closeModal('modal-delete-event')"
                    class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Cancel
            </button>
            <form method="post" action="<?= site_url('events/' . $event['id'] . '/delete') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition">
                    Yes, Delete Event
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
