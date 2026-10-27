<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight"><?= lang('App.category_management') ?></h1>
        <p class="text-slate-500 text-sm mt-1"><?= lang('App.categories_subtitle') ?></p>
    </div>
    <div>
        <button type="button" onclick="openModal('modal-add-category')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <i class="bi bi-folder-plus"></i>
            <span><?= lang('App.add_new_category') ?></span>
        </button>
    </div>
</div>

<!-- CATEGORIES GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    <?php foreach ($categories as $cat): ?>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="bi <?= esc($cat['icon'] ?: 'bi-tag') ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight"><?= esc($cat['name']) ?></h3>
                            <span class="text-[11px] font-semibold text-slate-400"><?= esc($cat['slug']) ?></span>
                        </div>
                    </div>
                    <?php if ($cat['provider_count'] == 0): ?>
                        <form method="post" action="<?= site_url('categories/' . $cat['id'] . '/delete') ?>" onsubmit="return confirm('<?= esc(lang('App.delete_category_confirm'), 'js') ?>');">
                            <?= csrf_field() ?>
                            <button type="submit" class="p-1 text-slate-300 hover:text-rose-600 transition" title="<?= lang('App.delete_category') ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 mb-4" style="min-height: 48px;">
                    <?= esc($cat['description'] ?? 'Event service specialty providers.') ?>
                </p>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                <span class="font-semibold text-slate-600">
                    <i class="bi bi-people-fill text-indigo-600 mr-1"></i><?= lang('App.vendors_count', [esc($cat['provider_count'])]) ?>
                </span>
                <a href="<?= site_url('providers?category_id=' . $cat['id']) ?>"
                   class="font-semibold text-indigo-600 hover:text-indigo-800 transition">
                    <?= lang('App.browse_vendors') ?> &rarr;
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
    $locale = service('request')->getLocale();
    $iconOptions = [
        'bi-tag'               => ['en' => 'General / Other',             'es' => 'General / Otro'],
        'bi-cup-hot'           => ['en' => 'Catering & Beverages',        'es' => 'Banquetería y Bebidas'],
        'bi-buildings'         => ['en' => 'Venues & Locations',          'es' => 'Salones y Lugares'],
        'bi-music-note-beamed' => ['en' => 'Music & DJ Entertainment',    'es' => 'Música y DJs'],
        'bi-camera'            => ['en' => 'Photography',                 'es' => 'Fotografía'],
        'bi-camera-reels'      => ['en' => 'Videography & Film',          'es' => 'Videografía y Cine'],
        'bi-flower1'           => ['en' => 'Florist & Floral Decor',      'es' => 'Floristería y Decoración'],
        'bi-speaker'           => ['en' => 'Audio & Sound Systems',       'es' => 'Audio y Sonido'],
        'bi-lightbulb'         => ['en' => 'Lighting & Stage FX',         'es' => 'Iluminación y Efectos'],
        'bi-truck'             => ['en' => 'Transport & Limousines',      'es' => 'Transporte y Limosinas'],
        'bi-cake2'             => ['en' => 'Cakes & Bakery',              'es' => 'Pastelería y Tortas'],
        'bi-balloon'           => ['en' => 'Party & Balloons',            'es' => 'Decoración con Globos'],
        'bi-shield-check'      => ['en' => 'Security & Staffing',         'es' => 'Seguridad y Personal'],
        'bi-person-badge'      => ['en' => 'Coordination & Planning',     'es' => 'Coordinación y Planificación'],
        'bi-envelope-paper'    => ['en' => 'Invitations & Stationery',     'es' => 'Invitaciones y Papelería'],
        'bi-palette'           => ['en' => 'Design & Styling',            'es' => 'Diseño y Ambientación'],
        'bi-gift'              => ['en' => 'Favors & Gifts',              'es' => 'Recuerdos y Regalos'],
        'bi-stars'             => ['en' => 'Special Effects & Sparklers', 'es' => 'Efectos Especiales y Chispas'],
        'bi-gem'               => ['en' => 'Jewelry & Bridal Attire',     'es' => 'Joyería y Vestuario'],
        'bi-scissors'          => ['en' => 'Hair & Makeup Styling',       'es' => 'Peluquería y Maquillaje'],
        'bi-fire'              => ['en' => 'BBQ & Open Flame Grill',      'es' => 'Parrilladas y Asados'],
        'bi-umbrella'          => ['en' => 'Outdoor Tents & Canopies',    'es' => 'Carpas y Toldos'],
    ];
?>

<!-- MODAL: ADD CATEGORY -->
<div id="modal-add-category" data-modal-backdrop class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl text-left border border-slate-200" onclick="event.stopPropagation()">
        <form method="post" action="<?= site_url('categories') ?>">
            <?= csrf_field() ?>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900"><?= lang('App.add_new_category') ?></h3>
                <button type="button" onclick="closeModal('modal-add-category')" class="text-slate-400 hover:text-slate-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        <?= lang('App.category_name') ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="<?= lang('App.category_name_placeholder') ?>"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        <?= lang('App.category_icon') ?>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-indigo-600 pointer-events-none text-base">
                            <i id="category-icon-preview" class="bi bi-tag"></i>
                        </span>
                        <select name="icon" id="category-icon-select"
                                onchange="document.getElementById('category-icon-preview').className = 'bi ' + this.value"
                                class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition text-sm cursor-pointer">
                            <?php foreach ($iconOptions as $iconClass => $names): ?>
                                <option value="<?= $iconClass ?>" <?= $iconClass === 'bi-tag' ? 'selected' : '' ?>>
                                    <?= esc($names[$locale] ?? $names['en']) ?> (<?= esc($iconClass) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1"><?= lang('App.category_icon_hint') ?></p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1"><?= lang('App.category_description') ?></label>
                    <textarea name="description" rows="3" placeholder="<?= lang('App.category_description') ?>..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-hidden focus:border-indigo-500 transition"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-add-category')"
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    <?= lang('App.cancel') ?>
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                    <?= lang('App.save') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
