<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?= lang('App.all_events') ?></h1>
        <p class="text-slate-500 text-sm mt-1"><?= lang('App.events_subtitle') ?></p>
    </div>
    <div>
        <a href="<?= site_url('events/new') ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <i class="bi bi-calendar-plus"></i>
            <span><?= lang('App.nav_new_event') ?></span>
        </a>
    </div>
</div>

<!-- FILTERS CARD -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs mb-6">
    <form method="get" action="<?= site_url('events') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
        <!-- Search -->
        <div class="lg:col-span-5 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search"
                   class="block w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                   placeholder="<?= lang('App.search_events_placeholder') ?>"
                   value="<?= esc($search ?? '') ?>">
        </div>

        <!-- Status Filter -->
        <div class="lg:col-span-3">
            <select name="status" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value=""><?= lang('App.all_statuses') ?></option>
                <option value="planning" <?= ($status ?? '') === 'planning' ? 'selected' : '' ?>>Planning</option>
                <option value="confirmed" <?= ($status ?? '') === 'confirmed' ? 'selected' : '' ?>><?= lang('App.status_confirmed') ?></option>
                <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>><?= lang('App.status_in_progress') ?></option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>><?= lang('App.status_completed') ?></option>
                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>><?= lang('App.status_cancelled') ?></option>
                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>><?= lang('App.status_draft') ?></option>
            </select>
        </div>

        <!-- Type Filter -->
        <div class="lg:col-span-2">
            <select name="type" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value=""><?= lang('App.all_types') ?></option>
                <option value="wedding" <?= ($type ?? '') === 'wedding' ? 'selected' : '' ?>><?= lang('App.type_wedding') ?></option>
                <option value="corporate" <?= ($type ?? '') === 'corporate' ? 'selected' : '' ?>><?= lang('App.type_corporate') ?></option>
                <option value="birthday" <?= ($type ?? '') === 'birthday' ? 'selected' : '' ?>><?= lang('App.type_birthday') ?></option>
                <option value="conference" <?= ($type ?? '') === 'conference' ? 'selected' : '' ?>><?= lang('App.type_conference') ?></option>
                <option value="anniversary" <?= ($type ?? '') === 'anniversary' ? 'selected' : '' ?>><?= lang('App.type_anniversary') ?></option>
                <option value="gala" <?= ($type ?? '') === 'gala' ? 'selected' : '' ?>><?= lang('App.type_gala') ?></option>
                <option value="other" <?= ($type ?? '') === 'other' ? 'selected' : '' ?>><?= lang('App.type_other') ?></option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="lg:col-span-2 flex items-center gap-2">
            <button type="submit" class="w-full px-3.5 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-2xs transition">
                <?= lang('App.filter') ?>
            </button>
            <?php if (!empty($search) || !empty($status) || !empty($type)): ?>
                <a href="<?= site_url('events') ?>" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 rounded-xl transition" title="<?= lang('App.clear_filters') ?>">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- EVENTS TABLE / LIST -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
    <?php if (empty($events)): ?>
        <div class="text-center py-16 px-4">
            <i class="bi bi-calendar-x text-5xl text-slate-300 block mb-3"></i>
            <h3 class="text-base font-bold text-slate-800"><?= lang('App.no_events_found') ?></h3>
            <p class="text-sm text-slate-500 mb-4 mt-1"><?= lang('App.create_first_event') ?></p>
            <a href="<?= site_url('events/new') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl">
                <?= lang('App.create_new_event') ?>
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5"><?= lang('App.col_event_client') ?></th>
                        <th class="px-4 py-3.5"><?= lang('App.col_date_time') ?></th>
                        <th class="px-4 py-3.5"><?= lang('App.col_location') ?></th>
                        <th class="px-4 py-3.5"><?= lang('App.col_status') ?></th>
                        <th class="px-4 py-3.5 min-w-[200px]"><?= lang('App.col_budget') ?></th>
                        <th class="px-4 py-3.5"><?= lang('App.col_providers') ?></th>
                        <th class="px-6 py-3.5 text-right"><?= lang('App.actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($events as $evt): ?>
                        <?php
                            $percentUsed = $evt['budget'] > 0 ? round(($evt['total_provider_cost'] / $evt['budget']) * 100) : 0;
                            $isOverBudget = $evt['total_provider_cost'] > $evt['budget'] && $evt['budget'] > 0;
                            $barColor = $isOverBudget ? 'bg-rose-500' : ($percentUsed > 80 ? 'bg-amber-500' : 'bg-indigo-600');

                            $statusClasses = [
                                'planning'    => 'bg-sky-50 text-sky-700 border-sky-200',
                                'confirmed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'completed'   => 'bg-slate-100 text-slate-700 border-slate-200',
                                'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                'draft'       => 'bg-gray-100 text-gray-700 border-gray-200',
                            ];
                            $badgeClass = $statusClasses[$evt['status']] ?? 'bg-slate-100 text-slate-700';

                            $statusKey = 'App.status_' . $evt['status'];
                            $translatedStatus = lang($statusKey);
                            if ($translatedStatus === $statusKey) {
                                $translatedStatus = ucwords(str_replace('_', ' ', $evt['status']));
                            }

                            $typeKey = 'App.type_' . $evt['event_type'];
                            $translatedType = lang($typeKey);
                            if ($translatedType === $typeKey) {
                                $translatedType = ucfirst($evt['event_type']);
                            }

                            $locale = service('request')->getLocale();
                            $evtTime = \CodeIgniter\I18n\Time::parse($evt['event_date'], null, $locale);
                            $formattedEvtDate = $evtTime->toLocalizedString(lang('App.date_format_medium'));
                        ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <a href="<?= site_url('events/' . $evt['id']) ?>" class="font-bold text-slate-900 hover:text-indigo-600 transition block text-base">
                                    <?= esc($evt['title']) ?>
                                </a>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span><i class="bi bi-person mr-1"></i><?= esc($evt['client_name']) ?></span>
                                    <span class="inline-block px-1.5 py-0.5 rounded-sm bg-slate-100 text-slate-600 border border-slate-200 font-medium text-[10px]"><?= esc($translatedType) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900"><?= esc($formattedEvtDate) ?></div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    <?php if ($evt['start_time']): ?>
                                        <i class="bi bi-clock mr-1"></i><?= substr($evt['start_time'], 0, 5) ?><?= $evt['end_time'] ? ' - ' . substr($evt['end_time'], 0, 5) : '' ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="truncate max-w-[160px] text-xs text-slate-700" title="<?= esc($evt['location_venue'] ?? lang('App.venue_tba')) ?>">
                                    <i class="bi bi-geo-alt text-slate-400 mr-1"></i><?= esc($evt['location_venue'] ?? lang('App.venue_tba')) ?>
                                </div>
                                <?php if ($evt['guest_count'] > 0): ?>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        <i class="bi bi-people mr-1"></i><?= esc($evt['guest_count']) ?> <?= lang('App.expected_guests') ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $badgeClass ?>">
                                    <?= esc($translatedStatus) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-slate-500"><?= lang('App.spent') ?>: <strong class="<?= $isOverBudget ? 'text-rose-600' : 'text-slate-800' ?>">$<?= number_format($evt['total_provider_cost'], 0) ?></strong></span>
                                    <span class="text-slate-400"><?= lang('App.budget') ?>: $<?= number_format($evt['budget'], 0) ?></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="<?= $barColor ?> h-1.5 rounded-full transition-all" style="width: <?= min($percentUsed, 100) ?>%"></div>
                                </div>
                                <div class="text-right text-[11px] mt-0.5 <?= $isOverBudget ? 'text-rose-600 font-bold' : 'text-slate-400' ?>">
                                    <?= lang('App.percent_allocated', [$percentUsed]) ?>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold">
                                    <i class="bi bi-people-fill text-indigo-600"></i><?= lang('App.providers_hired', [esc($evt['provider_count'])]) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= site_url('events/' . $evt['id'] . '/export') ?>"
                                       class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="<?= lang('App.export_excel') ?>">
                                        <i class="bi bi-file-earmark-excel"></i>
                                    </a>
                                    <a href="<?= site_url('events/' . $evt['id']) ?>"
                                       class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="<?= lang('App.view_details') ?>">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('events/' . $evt['id'] . '/edit') ?>"
                                       class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="<?= lang('App.edit') ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" onclick="openModal('delete-modal-<?= $evt['id'] ?>')"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="<?= lang('App.delete') ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- DELETE MODAL -->
                                <div id="delete-modal-<?= $evt['id'] ?>" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                            </div>
                                            <button type="button" onclick="closeModal('delete-modal-<?= $evt['id'] ?>')" class="text-slate-400 hover:text-slate-600">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900"><?= lang('App.delete_event') ?>?</h3>
                                        <p class="text-sm text-slate-500 mt-2">
                                            <?= lang('App.delete_event_confirm', ['<strong>' . esc($evt['title']) . '</strong>']) ?>
                                        </p>
                                        <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                                            <button type="button" onclick="closeModal('delete-modal-<?= $evt['id'] ?>')"
                                                    class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                                <?= lang('App.cancel') ?>
                                            </button>
                                            <form method="post" action="<?= site_url('events/' . $evt['id'] . '/delete') ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition">
                                                    <?= lang('App.yes_delete') ?>
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

<?= $this->endSection() ?>
