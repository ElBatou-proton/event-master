<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = !empty($event);
    $actionUrl = $isEdit ? site_url('events/' . $event['id']) : site_url('events');
?>

<div class="max-w-4xl mx-auto">
    <!-- BREADCRUMB -->
    <nav class="flex mb-4 text-xs font-semibold text-slate-500 gap-2">
        <a href="<?= site_url('events') ?>" class="hover:text-indigo-600"><?= lang('App.nav_events') ?></a>
        <span>/</span>
        <span class="text-slate-800"><?= $isEdit ? lang('App.edit_event') : lang('App.create_new_event') ?></span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?= $isEdit ? lang('App.edit_event') : lang('App.create_new_event') ?></h1>
            <p class="text-slate-500 text-sm mt-1"><?= $isEdit ? lang('App.form_event_subtitle_edit') : lang('App.form_event_subtitle_new') ?></p>
        </div>
        <a href="<?= $isEdit ? site_url('events/' . $event['id']) : site_url('events') ?>"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 shadow-2xs transition">
            <i class="bi bi-arrow-left"></i> <?= lang('App.cancel') ?>
        </a>
    </div>

    <form method="post" action="<?= $actionUrl ?>" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 sm:p-8 space-y-8">
        <?= csrf_field() ?>

        <!-- SECTION 1: EVENT BASICS -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-info-circle text-indigo-600"></i> <?= lang('App.event_details') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-5 mt-5">
                <div class="lg:col-span-8">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.event_title') ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           placeholder="<?= lang('App.event_title_placeholder') ?>"
                           value="<?= old('title', $event['title'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.event_type') ?>
                    </label>
                    <select name="event_type"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <?php
                            $types = [
                                'wedding'     => lang('App.type_wedding'),
                                'corporate'   => lang('App.type_corporate'),
                                'birthday'    => lang('App.type_birthday'),
                                'conference'  => lang('App.type_conference'),
                                'anniversary' => lang('App.type_anniversary'),
                                'gala'        => lang('App.type_gala'),
                                'other'       => lang('App.type_other'),
                            ];
                            $selectedType = old('event_type', $event['event_type'] ?? 'wedding');
                        ?>
                        <?php foreach ($types as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $selectedType === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.event_date') ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="event_date" required
                           value="<?= old('event_date', $event['event_date'] ?? date('Y-m-d', strtotime('+30 days'))) ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.start_time') ?>
                    </label>
                    <input type="time" name="start_time"
                           value="<?= old('start_time', isset($event['start_time']) ? substr($event['start_time'], 0, 5) : '18:00') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.end_time') ?>
                    </label>
                    <input type="time" name="end_time"
                           value="<?= old('end_time', isset($event['end_time']) ? substr($event['end_time'], 0, 5) : '23:30') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-8">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.location_venue') ?>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <input type="text" name="location_venue"
                               placeholder="<?= lang('App.location_placeholder') ?>"
                               value="<?= old('location_venue', $event['location_venue'] ?? '') ?>"
                               class="w-full pl-9 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.status') ?>
                    </label>
                    <select name="status"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <?php
                            $statuses = [
                                'draft'       => lang('App.status_draft'),
                                'planning'    => 'Planning',
                                'confirmed'   => lang('App.status_confirmed'),
                                'in_progress' => lang('App.status_in_progress'),
                                'completed'   => lang('App.status_completed'),
                                'cancelled'   => lang('App.status_cancelled'),
                            ];
                            $selectedStatus = old('status', $event['status'] ?? 'planning');
                        ?>
                        <?php foreach ($statuses as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: CLIENT DETAILS & BUDGET -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-person text-indigo-600"></i> <?= lang('App.client_info') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-5 mt-5">
                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.client_name') ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="client_name" required
                           placeholder="e.g. Sarah Jenkins"
                           value="<?= old('client_name', $event['client_name'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.client_email') ?>
                    </label>
                    <input type="email" name="client_email"
                           placeholder="client@example.com"
                           value="<?= old('client_email', $event['client_email'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.client_phone') ?>
                    </label>
                    <input type="text" name="client_phone"
                           placeholder="+1 (555) 000-0000"
                           value="<?= old('client_phone', $event['client_phone'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.expected_guests') ?>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-people"></i>
                        </span>
                        <input type="number" min="0" name="guest_count"
                               placeholder="150"
                               value="<?= old('guest_count', $event['guest_count'] ?? '0') ?>"
                               class="w-full pl-9 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.budget') ?>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none font-bold">
                            $
                        </span>
                        <input type="number" step="0.01" min="0" name="budget"
                               placeholder="15000.00"
                               value="<?= old('budget', $event['budget'] ?? '0.00') ?>"
                               class="w-full pl-8 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: NOTES & DESCRIPTION -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-journal-text text-indigo-600"></i> <?= lang('App.description') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.description') ?>
                    </label>
                    <textarea name="description" rows="4"
                              placeholder="<?= lang('App.description_placeholder') ?>"
                              class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"><?= old('description', $event['description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.internal_notes') ?>
                    </label>
                    <textarea name="notes" rows="4"
                              placeholder="<?= lang('App.internal_notes_placeholder') ?>"
                              class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"><?= old('notes', $event['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="<?= $isEdit ? site_url('events/' . $event['id']) : site_url('events') ?>"
               class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                <?= lang('App.cancel') ?>
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition">
                <i class="bi bi-check2 mr-1"></i> <?= $isEdit ? lang('App.save_changes') : lang('App.create_new_event') ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
