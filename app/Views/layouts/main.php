<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? lang('App.brand_name')) ?> | CodeIgniter 4</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap Icons (for crisp service & category icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- LOGO & BRAND -->
                <div class="flex items-center gap-8">
                    <a href="<?= site_url('/') ?>" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-sm group-hover:bg-indigo-700 transition">
                            <i class="bi bi-calendar-event-fill text-lg"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">
                            Event<span class="text-indigo-600">Master</span>
                        </span>
                    </a>

                    <!-- DESKTOP NAVIGATION -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="<?= site_url('/') ?>"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= uri_string() === '' ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <i class="bi bi-speedometer2 mr-1.5"></i><?= lang('App.nav_dashboard') ?>
                        </a>
                        <a href="<?= site_url('events') ?>"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= str_starts_with(uri_string(), 'events') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <i class="bi bi-calendar-check mr-1.5"></i><?= lang('App.nav_events') ?>
                        </a>
                        <a href="<?= site_url('providers') ?>"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= str_starts_with(uri_string(), 'providers') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <i class="bi bi-people mr-1.5"></i><?= lang('App.nav_providers') ?>
                        </a>
                        <a href="<?= site_url('categories') ?>"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= str_starts_with(uri_string(), 'categories') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <i class="bi bi-grid mr-1.5"></i><?= lang('App.nav_categories') ?>
                        </a>
                    </nav>
                </div>

                <!-- RIGHT ACTION BUTTONS -->
                <div class="hidden md:flex items-center gap-2.5">
                    <!-- LANGUAGE SELECTOR -->
                    <?php $currentLocale = service('request')->getLocale(); ?>
                    <div class="relative inline-block text-left" id="lang-menu-container">
                        <button type="button" onclick="toggleLangMenu()"
                                class="inline-flex items-center gap-1.5 px-2.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-2xs transition"
                                title="<?= lang('App.language') ?>">
                            <i class="bi bi-translate text-indigo-600"></i>
                            <span><?= $currentLocale === 'es' ? 'ES' : 'EN' ?></span>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-36 rounded-xl bg-white shadow-lg border border-slate-100 py-1 z-50">
                            <a href="<?= site_url('lang/en') ?>"
                               class="flex items-center justify-between px-3 py-1.5 text-xs font-medium <?= $currentLocale === 'en' ? 'text-indigo-600 bg-indigo-50/50 font-semibold' : 'text-slate-700 hover:bg-slate-50' ?>">
                                <span>English</span>
                                <?php if ($currentLocale === 'en'): ?><i class="bi bi-check2 text-indigo-600"></i><?php endif; ?>
                            </a>
                            <a href="<?= site_url('lang/es') ?>"
                               class="flex items-center justify-between px-3 py-1.5 text-xs font-medium <?= $currentLocale === 'es' ? 'text-indigo-600 bg-indigo-50/50 font-semibold' : 'text-slate-700 hover:bg-slate-50' ?>">
                                <span>Español</span>
                                <?php if ($currentLocale === 'es'): ?><i class="bi bi-check2 text-indigo-600"></i><?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <a href="<?= site_url('events/new') ?>"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                        <i class="bi bi-plus-lg"></i>
                        <span><?= lang('App.nav_new_event') ?></span>
                    </a>
                    <a href="<?= site_url('providers/new') ?>"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-2xs hover:bg-slate-50 transition">
                        <i class="bi bi-person-plus"></i>
                        <span><?= lang('App.nav_new_provider') ?></span>
                    </a>
                </div>

                <!-- MOBILE MENU TOGGLE -->
                <div class="flex md:hidden">
                    <button type="button" onclick="toggleMobileNav()"
                            class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU DROPDOWN -->
        <div id="mobile-nav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
            <a href="<?= site_url('/') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= uri_string() === '' ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="bi bi-speedometer2 mr-2"></i><?= lang('App.nav_dashboard') ?>
            </a>
            <a href="<?= site_url('events') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= str_starts_with(uri_string(), 'events') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="bi bi-calendar-check mr-2"></i><?= lang('App.nav_events') ?>
            </a>
            <a href="<?= site_url('providers') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= str_starts_with(uri_string(), 'providers') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="bi bi-people mr-2"></i><?= lang('App.nav_providers') ?>
            </a>
            <a href="<?= site_url('categories') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= str_starts_with(uri_string(), 'categories') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="bi bi-grid mr-2"></i><?= lang('App.nav_categories') ?>
            </a>
            <div class="pt-3 border-t border-slate-200 flex flex-col gap-2">
                <div class="flex items-center justify-between px-1 py-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider"><?= lang('App.language') ?></span>
                    <div class="flex items-center gap-1.5">
                        <a href="<?= site_url('lang/en') ?>" class="px-2.5 py-1 text-xs font-semibold rounded-md <?= $currentLocale === 'en' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>">EN</a>
                        <a href="<?= site_url('lang/es') ?>" class="px-2.5 py-1 text-xs font-semibold rounded-md <?= $currentLocale === 'es' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>">ES</a>
                    </div>
                </div>
                <a href="<?= site_url('events/new') ?>" class="w-full text-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="bi bi-plus-lg mr-1"></i><?= lang('App.nav_new_event') ?>
                </a>
                <a href="<?= site_url('providers/new') ?>" class="w-full text-center px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    <i class="bi bi-person-plus mr-1"></i><?= lang('App.nav_new_provider') ?>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN BODY CONTAINER -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- FLASH ALERTS -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="flash-alert-success" class="mb-5 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-2xs">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                    <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
                </div>
                <button type="button" onclick="document.getElementById('flash-alert-success').remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-alert-error" class="mb-5 flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-2xs">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg"></i>
                    <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
                </div>
                <button type="button" onclick="document.getElementById('flash-alert-error').remove()" class="text-rose-500 hover:text-rose-700 p-1">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div id="flash-alert-errors" class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-2xs">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 font-semibold text-sm text-rose-900">
                        <i class="bi bi-exclamation-octagon-fill text-rose-600"></i>
                        <span><?= lang('App.errors_heading') ?></span>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-alert-errors').remove()" class="text-rose-500 hover:text-rose-700 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 text-rose-700 pl-2">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- PAGE CONTENT -->
        <?= $this->renderSection('content') ?>

    </main>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-t border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800"><?= lang('App.brand_name') ?></span>
                <span>&bull; <?= lang('App.footer_powered', [CodeIgniter\CodeIgniter::CI_VERSION]) ?></span>
            </div>
            <div>
                <span><?= lang('App.footer_tagline') ?></span>
            </div>
        </div>
    </footer>

    <!-- LIGHTWEIGHT JAVASCRIPT HELPERS (MODALS, MOBILE MENU, AJAX) -->
    <script>
        function toggleMobileNav() {
            const nav = document.getElementById('mobile-nav');
            nav.classList.toggle('hidden');
        }

        function toggleLangMenu() {
            const dropdown = document.getElementById('lang-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close language dropdown on outside click
        window.addEventListener('click', function(e) {
            const container = document.getElementById('lang-menu-container');
            const dropdown = document.getElementById('lang-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Modal open / close handlers
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Close modal when clicking on backdrop
        window.addEventListener('click', function(e) {
            if (e.target.hasAttribute('data-modal-backdrop')) {
                closeModal(e.target.id);
            }
        });

        // Close modal on ESC key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[data-modal-backdrop]:not(.hidden)').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // AJAX Task toggle
        function toggleTaskStatus(taskId, checkboxEl) {
            fetch('<?= site_url('events/tasks/') ?>' + taskId + '/toggle', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network error');
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    if (checkboxEl) {
                        checkboxEl.checked = (data.newStatus === 'completed');
                    }
                    const label = document.getElementById('task-label-' + taskId);
                    if (label) {
                        if (data.newStatus === 'completed') {
                            label.classList.add('line-through', 'text-slate-400');
                            label.classList.remove('text-slate-800');
                        } else {
                            label.classList.remove('line-through', 'text-slate-400');
                            label.classList.add('text-slate-800');
                        }
                    }

                    // Update Checklist & Milestone Tasks counter badge
                    const counterBadge = document.getElementById('task-counter-badge');
                    if (counterBadge && data.totalTasks !== undefined && data.completedTasks !== undefined) {
                        const doneText = counterBadge.getAttribute('data-done-text') || 'Done';
                        counterBadge.textContent = `${data.completedTasks} / ${data.totalTasks} ${doneText} (${data.taskPercentage}%)`;
                    }

                    // Update Checklist & Milestone Tasks progress bar
                    const progressBar = document.getElementById('task-progress-bar');
                    if (progressBar && data.taskPercentage !== undefined) {
                        progressBar.style.width = data.taskPercentage + '%';
                    }

                    // Update Dashboard pending tasks counter if present
                    const pendingCounter = document.getElementById('dashboard-pending-counter');
                    const pendingList = document.getElementById('pending-tasks-list');
                    if (pendingCounter && pendingList) {
                        const remaining = pendingList.querySelectorAll('input[type="checkbox"]:not(:checked)').length;
                        const pendingText = pendingCounter.getAttribute('data-pending-text') || 'pending';
                        pendingCounter.textContent = `${remaining} ${pendingText}`;
                    }
                } else {
                    if (checkboxEl) {
                        checkboxEl.checked = !checkboxEl.checked;
                    }
                    console.error('Task toggle failed:', data.message);
                }
            })
            .catch(err => {
                if (checkboxEl) {
                    checkboxEl.checked = !checkboxEl.checked;
                }
                console.error('Task toggle error:', err);
            });
        }
    </script>
</body>
</html>
