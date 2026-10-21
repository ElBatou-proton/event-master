<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Service Providers Directory</h1>
        <p class="text-slate-500 text-sm mt-1">Manage caterers, venues, DJs, photographers, florists, and production crews.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?= site_url('categories') ?>"
           class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 shadow-2xs transition">
            <i class="bi bi-grid"></i> Categories
        </a>
        <a href="<?= site_url('providers/new') ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <i class="bi bi-person-plus-fill"></i>
            <span>Register Provider</span>
        </a>
    </div>
</div>

<!-- FILTERS CARD -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs mb-6">
    <form method="get" action="<?= site_url('providers') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
        <!-- Search -->
        <div class="lg:col-span-5 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search"
                   class="block w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                   placeholder="Search provider, contact person, city..."
                   value="<?= esc($search ?? '') ?>">
        </div>

        <!-- Category -->
        <div class="lg:col-span-3">
            <select name="category_id" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($categoryId ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status -->
        <div class="lg:col-span-2">
            <select name="status" onchange="this.form.submit()"
                    class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="">All Statuses</option>
                <option value="preferred" <?= ($status ?? '') === 'preferred' ? 'selected' : '' ?>>Preferred</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="lg:col-span-2 flex items-center gap-2">
            <button type="submit" class="w-full px-3.5 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-2xs transition">
                Filter
            </button>
            <?php if (!empty($search) || !empty($categoryId) || !empty($status)): ?>
                <a href="<?= site_url('providers') ?>" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 rounded-xl transition" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- PROVIDERS GRID -->
<?php if (empty($providers)): ?>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs text-center py-16 px-4">
        <i class="bi bi-person-x text-5xl text-slate-300 block mb-3"></i>
        <h3 class="text-base font-bold text-slate-800">No service providers found</h3>
        <p class="text-sm text-slate-500 mb-4 mt-1">Try adjusting your filters or register a new vendor in the directory.</p>
        <a href="<?= site_url('providers/new') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl">
            Register Provider
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($providers as $prov): ?>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <!-- CATEGORY & RATING -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-bold text-slate-700">
                            <i class="bi <?= esc($prov['category_icon'] ?? 'bi-tag') ?> text-indigo-600"></i>
                            <?= esc($prov['category_name'] ?? 'General') ?>
                        </span>
                        <div class="flex items-center gap-2">
                            <?php if ($prov['status'] === 'preferred'): ?>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Preferred</span>
                            <?php endif; ?>
                            <span class="text-amber-500 text-xs font-bold tracking-wider">
                                <?= str_repeat('★', $prov['rating'] ?? 5) ?>
                            </span>
                        </div>
                    </div>

                    <!-- NAME & CONTACT -->
                    <h3 class="text-lg font-bold text-slate-900 leading-snug">
                        <a href="<?= site_url('providers/' . $prov['id']) ?>" class="hover:text-indigo-600 transition">
                            <?= esc($prov['name']) ?>
                        </a>
                    </h3>

                    <?php if ($prov['contact_person'] || $prov['city']): ?>
                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                            <?php if ($prov['contact_person']): ?>
                                <span><i class="bi bi-person mr-1"></i><?= esc($prov['contact_person']) ?></span>
                            <?php endif; ?>
                            <?php if ($prov['city']): ?>
                                <span>&bull; <i class="bi bi-geo-alt"></i> <?= esc($prov['city']) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- NOTES -->
                    <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed" style="min-height: 32px;">
                        <?= esc($prov['notes'] ?? 'Available for event bookings and custom packages.') ?>
                    </p>

                    <!-- CONTACT PILLS -->
                    <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1.5 text-xs">
                        <?php if ($prov['phone']): ?>
                            <div class="flex items-center gap-2 truncate">
                                <i class="bi bi-telephone text-indigo-600"></i>
                                <a href="tel:<?= esc($prov['phone']) ?>" class="text-slate-700 hover:text-indigo-600 font-medium"><?= esc($prov['phone']) ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($prov['email']): ?>
                            <div class="flex items-center gap-2 truncate">
                                <i class="bi bi-envelope text-indigo-600"></i>
                                <a href="mailto:<?= esc($prov['email']) ?>" class="text-slate-700 hover:text-indigo-600 font-medium"><?= esc($prov['email']) ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($prov['website']): ?>
                            <div class="flex items-center gap-2 truncate">
                                <i class="bi bi-globe text-indigo-600"></i>
                                <a href="<?= esc($prov['website']) ?>" target="_blank" rel="noopener" class="text-slate-700 hover:text-indigo-600 font-medium">Website</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                    <span class="text-xs font-extrabold text-emerald-600"><?= esc($prov['price_range'] ?? '$$') ?> Tier</span>
                    <div class="flex items-center gap-1.5">
                        <a href="<?= site_url('providers/' . $prov['id']) ?>"
                           class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            View
                        </a>
                        <a href="<?= site_url('providers/' . $prov['id'] . '/edit') ?>"
                           class="p-1 text-slate-400 hover:text-slate-700 rounded-lg transition" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" onclick="openModal('delete-prov-modal-<?= $prov['id'] ?>')"
                                class="p-1 text-slate-400 hover:text-rose-600 rounded-lg transition" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- DELETE MODAL -->
                <div id="delete-prov-modal-<?= $prov['id'] ?>" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <button type="button" onclick="closeModal('delete-prov-modal-<?= $prov['id'] ?>')" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Delete Provider?</h3>
                        <p class="text-sm text-slate-500 mt-2">
                            Are you sure you want to delete <strong><?= esc($prov['name']) ?></strong> from the directory?
                        </p>
                        <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                            <button type="button" onclick="closeModal('delete-prov-modal-<?= $prov['id'] ?>')"
                                    class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                Cancel
                            </button>
                            <form method="post" action="<?= site_url('providers/' . $prov['id'] . '/delete') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition">
                                    Yes, Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
