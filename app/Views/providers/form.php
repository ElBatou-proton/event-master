<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = !empty($provider);
    $actionUrl = $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers');
?>

<div class="max-w-4xl mx-auto">
    <!-- BREADCRUMB -->
    <nav class="flex mb-4 text-xs font-semibold text-slate-500 gap-2">
        <a href="<?= site_url('providers') ?>" class="hover:text-indigo-600"><?= lang('App.nav_providers') ?></a>
        <span>/</span>
        <span class="text-slate-800"><?= $isEdit ? lang('App.edit_provider') : lang('App.create_new_provider') ?></span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?= $isEdit ? lang('App.edit_provider') : lang('App.create_new_provider') ?></h1>
            <p class="text-slate-500 text-sm mt-1"><?= $isEdit ? lang('App.form_provider_subtitle_edit') : lang('App.form_provider_subtitle_new') ?></p>
        </div>
        <a href="<?= $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers') ?>"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 shadow-2xs transition">
            <i class="bi bi-arrow-left"></i> <?= lang('App.cancel') ?>
        </a>
    </div>

    <form method="post" action="<?= $actionUrl ?>" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 sm:p-8 space-y-8">
        <?= csrf_field() ?>

        <!-- SECTION 1: BASIC PROFILE -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-shop text-indigo-600"></i> <?= lang('App.provider_details') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-5 mt-5">
                <div class="lg:col-span-7">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.provider_name') ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required
                           placeholder="<?= lang('App.provider_name_placeholder') ?>"
                           value="<?= old('name', $provider['name'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.category') ?>
                    </label>
                    <select name="category_id"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value=""><?= lang('App.choose_category') ?></option>
                        <?php $selectedCat = old('category_id', $provider['category_id'] ?? ''); ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (string)$selectedCat === (string)$cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.col_price_range') ?>
                    </label>
                    <select name="price_range"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <?php
                            $ranges = [
                                '$'    => lang('App.tier_budget'),
                                '$$'   => lang('App.tier_moderate'),
                                '$$$'  => lang('App.tier_premium'),
                                '$$$$' => lang('App.tier_luxury'),
                            ];
                            $selectedPrice = old('price_range', $provider['price_range'] ?? '$$');
                        ?>
                        <?php foreach ($ranges as $k => $lbl): ?>
                            <option value="<?= $k ?>" <?= $selectedPrice === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.rating_label') ?>
                    </label>
                    <select name="rating"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <?php $selectedRating = (int) old('rating', $provider['rating'] ?? 5); ?>
                        <option value="5" <?= $selectedRating === 5 ? 'selected' : '' ?>>★★★★★ (<?= lang('App.stars_count', [5]) ?>)</option>
                        <option value="4" <?= $selectedRating === 4 ? 'selected' : '' ?>>★★★★☆ (<?= lang('App.stars_count', [4]) ?>)</option>
                        <option value="3" <?= $selectedRating === 3 ? 'selected' : '' ?>>★★★☆☆ (<?= lang('App.stars_count', [3]) ?>)</option>
                        <option value="2" <?= $selectedRating === 2 ? 'selected' : '' ?>>★★☆☆☆ (<?= lang('App.stars_count', [2]) ?>)</option>
                        <option value="1" <?= $selectedRating === 1 ? 'selected' : '' ?>>★☆☆☆☆ (<?= lang('App.star_single') ?>)</option>
                    </select>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.status') ?>
                    </label>
                    <select name="status"
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <?php
                            $statuses = [
                                'preferred' => lang('App.status_preferred_vendor'),
                                'active'    => lang('App.status_active'),
                                'inactive'  => lang('App.status_inactive'),
                            ];
                            $selectedStatus = old('status', $provider['status'] ?? 'active');
                        ?>
                        <?php foreach ($statuses as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: CONTACT INFORMATION -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-person-lines-fill text-indigo-600"></i> <?= lang('App.contact_information') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-5 mt-5">
                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.contact_person') ?>
                    </label>
                    <input type="text" name="contact_person"
                           placeholder="<?= lang('App.contact_person_placeholder') ?>"
                           value="<?= old('contact_person', $provider['contact_person'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.email') ?>
                    </label>
                    <input type="email" name="email"
                           placeholder="booking@provider.com"
                           value="<?= old('email', $provider['email'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.phone') ?>
                    </label>
                    <input type="text" name="phone"
                           placeholder="+1 (555) 123-4567"
                           value="<?= old('phone', $provider['phone'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.website') ?>
                    </label>
                    <input type="url" name="website"
                           placeholder="https://provider.com"
                           value="<?= old('website', $provider['website'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-8">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.street_address') ?>
                    </label>
                    <input type="text" name="address"
                           placeholder="<?= lang('App.street_address_placeholder') ?>"
                           value="<?= old('address', $provider['address'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        <?= lang('App.city_service_area') ?>
                    </label>
                    <input type="text" name="city"
                           placeholder="<?= lang('App.city_placeholder') ?>"
                           value="<?= old('city', $provider['city'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>
        </div>

        <!-- SECTION 3: NOTES & SPECIALTIES -->
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-file-text text-indigo-600"></i> <?= lang('App.service_capabilities_specialties') ?>
            </h3>

            <div class="mt-5">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    <?= lang('App.specialties_label') ?>
                </label>
                <textarea name="notes" rows="4"
                          placeholder="<?= lang('App.specialties_placeholder') ?>"
                          class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"><?= old('notes', $provider['notes'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="<?= $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers') ?>"
               class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                <?= lang('App.cancel') ?>
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition">
                <i class="bi bi-check2 mr-1"></i> <?= $isEdit ? lang('App.save_changes') : lang('App.create_new_provider') ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
