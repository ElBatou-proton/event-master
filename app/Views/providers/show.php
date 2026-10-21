<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- BREADCRUMB & TOP ACTIONS -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <nav class="flex text-xs font-semibold text-slate-500 gap-2 items-center">
        <a href="<?= site_url('providers') ?>" class="hover:text-indigo-600">Providers</a>
        <span>/</span>
        <span class="text-slate-800"><?= esc($provider['name']) ?></span>
    </nav>
    <div class="flex items-center gap-2">
        <a href="<?= site_url('providers/' . $provider['id'] . '/edit') ?>"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-2xs transition">
            <i class="bi bi-pencil"></i> Edit Profile
        </a>
        <button type="button" onclick="openModal('modal-delete-provider')"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition" title="Delete Provider">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

<!-- PROVIDER PROFILE CARD -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
        <div class="lg:col-span-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-bold text-slate-700">
                    <i class="bi <?= esc($provider['category_icon'] ?? 'bi-tag') ?> text-indigo-600"></i>
                    <?= esc($provider['category_name'] ?? 'General Vendor') ?>
                </span>
                <span class="text-amber-500 text-xs font-bold tracking-wider">
                    <?= str_repeat('★', $provider['rating'] ?? 5) ?> (<?= $provider['rating'] ?>/5)
                </span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <?= esc($provider['price_range']) ?> Tier
                </span>
                <?php if ($provider['status'] === 'preferred'): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        <i class="bi bi-award-fill mr-1"></i> Preferred Partner
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                <?= esc($provider['name']) ?>
            </h1>

            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-600">
                <?php if ($provider['contact_person']): ?>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-person-fill text-indigo-600 text-sm"></i>
                        <strong class="text-slate-900"><?= esc($provider['contact_person']) ?></strong>
                    </div>
                <?php endif; ?>
                <?php if ($provider['city']): ?>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-geo-alt-fill text-rose-500 text-sm"></i>
                        <span><?= esc($provider['city']) ?><?= $provider['address'] ? ' (' . esc($provider['address']) . ')' : '' ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($provider['notes'])): ?>
                <div class="mt-4 p-3 rounded-xl bg-slate-50 border-l-4 border-indigo-600 text-xs text-slate-600 leading-relaxed">
                    <strong class="text-slate-800">Service Capabilities & Specs:</strong><br>
                    <?= nl2br(esc($provider['notes'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- DIRECT CONNECT ACTIONS -->
        <div class="lg:col-span-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <i class="bi bi-chat-dots-fill text-indigo-600"></i> Direct Connect
                </h4>
                <div class="flex flex-col gap-2">
                    <?php if ($provider['phone']): ?>
                        <a href="tel:<?= esc($provider['phone']) ?>"
                           class="flex items-center gap-2.5 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:text-indigo-600 hover:border-indigo-200 transition shadow-2xs">
                            <i class="bi bi-telephone-fill text-indigo-600"></i>
                            <span class="truncate"><?= esc($provider['phone']) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($provider['email']): ?>
                        <a href="mailto:<?= esc($provider['email']) ?>"
                           class="flex items-center gap-2.5 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:text-indigo-600 hover:border-indigo-200 transition shadow-2xs">
                            <i class="bi bi-envelope-fill text-indigo-600"></i>
                            <span class="truncate"><?= esc($provider['email']) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($provider['website']): ?>
                        <a href="<?= esc($provider['website']) ?>" target="_blank" rel="noopener"
                           class="flex items-center gap-2.5 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:text-indigo-600 hover:border-indigo-200 transition shadow-2xs">
                            <i class="bi bi-globe text-indigo-600"></i>
                            <span class="truncate">Visit Official Website</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOKED EVENTS HISTORY -->
<?php
    $totalContracted = 0;
    $totalReceived = 0;
    foreach ($bookedEvents as $b) {
        $totalContracted += (float) $b['agreed_cost'];
        $totalReceived += (float) $b['deposit_paid'];
    }
?>
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <i class="bi bi-calendar-check text-indigo-600 text-lg"></i>
            <h2 class="text-base font-bold text-slate-900">Event Bookings History</h2>
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700">
                <?= count($bookedEvents) ?> events
            </span>
        </div>
        <div class="text-xs text-slate-500 font-medium">
            Contracted Revenue: <strong class="text-slate-900">$<?= number_format($totalContracted, 2) ?></strong>
            &bull; Cleared: <strong class="text-emerald-600">$<?= number_format($totalReceived, 2) ?></strong>
        </div>
    </div>

    <?php if (empty($bookedEvents)): ?>
        <div class="text-center py-12 px-4">
            <i class="bi bi-calendar-x text-4xl text-slate-300 block mb-2"></i>
            <p class="text-sm font-medium text-slate-600 mb-3">This provider has not been assigned to any events yet.</p>
            <a href="<?= site_url('events') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-indigo-600 text-white rounded-lg">
                Go to Events to Hire
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Event & Client</th>
                        <th class="px-4 py-3.5">Event Date</th>
                        <th class="px-4 py-3.5">Service Description</th>
                        <th class="px-4 py-3.5">Agreed Fee</th>
                        <th class="px-4 py-3.5">Deposit Paid</th>
                        <th class="px-4 py-3.5">Payment</th>
                        <th class="px-4 py-3.5">Contract</th>
                        <th class="px-6 py-3.5 text-right">Event Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($bookedEvents as $bev): ?>
                        <?php
                            $payBadge = [
                                'paid'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'pending' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                            $payClass = $payBadge[$bev['payment_status']] ?? 'bg-slate-100 text-slate-700';
                        ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <a href="<?= site_url('events/' . $bev['event_id']) ?>" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                    <?= esc($bev['event_title']) ?>
                                </a>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    <i class="bi bi-person mr-1"></i><?= esc($bev['client_name']) ?>
                                    <span class="inline-block px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-[10px] font-semibold border border-slate-200 capitalize ms-1"><?= esc($bev['event_type']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900"><?= date('M j, Y', strtotime($bev['event_date'])) ?></div>
                                <span class="text-[11px] text-slate-400 capitalize"><?= esc(str_replace('_', ' ', $bev['event_status'])) ?></span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-xs font-medium text-slate-800"><?= esc($bev['service_description'] ?? 'Event Services') ?></div>
                                <?php if (!empty($bev['notes'])): ?>
                                    <div class="text-[11px] text-slate-400 truncate max-w-[200px]" title="<?= esc($bev['notes']) ?>"><?= esc($bev['notes']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap font-bold text-slate-900">
                                $<?= number_format($bev['agreed_cost'], 2) ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap font-semibold text-emerald-600">
                                $<?= number_format($bev['deposit_paid'], 2) ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border <?= $payClass ?> capitalize">
                                    <?= esc($bev['payment_status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 capitalize">
                                    <?= esc($bev['contract_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="<?= site_url('events/' . $bev['event_id']) ?>"
                                   class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                    View Event &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- DELETE PROVIDER MODAL -->
<div id="modal-delete-provider" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <button type="button" onclick="closeModal('modal-delete-provider')" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Delete Provider?</h3>
        <p class="text-sm text-slate-500 mt-2">
            Are you sure you want to permanently delete <strong><?= esc($provider['name']) ?></strong>? This will remove the provider from the directory.
        </p>
        <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
            <button type="button" onclick="closeModal('modal-delete-provider')"
                    class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Cancel
            </button>
            <form method="post" action="<?= site_url('providers/' . $provider['id'] . '/delete') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition">
                    Yes, Delete Provider
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
