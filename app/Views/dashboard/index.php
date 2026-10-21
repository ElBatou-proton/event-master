<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Event Planning Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Overview of upcoming events, vendor commitments, budgets, and tasks.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?= site_url('events/new') ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-xs transition">
            <i class="bi bi-calendar-plus"></i>
            <span>Create Event</span>
        </a>
        <a href="<?= site_url('providers/new') ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl shadow-2xs transition">
            <i class="bi bi-person-plus"></i>
            <span>Register Provider</span>
        </a>
    </div>
</div>

<!-- TOP METRIC CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Upcoming Events</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1"><?= esc($stats['upcoming_events']) ?></h3>
                <p class="text-xs text-slate-500 mt-1"><?= esc($stats['total_events']) ?> total events registered</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="bi bi-calendar-event"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Event Budget</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">$<?= number_format($stats['total_budget'], 2) ?></h3>
                <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                    <i class="bi bi-check-circle-fill"></i> Target budgets active
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Committed Costs</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">$<?= number_format($stats['total_committed'], 2) ?></h3>
                <p class="text-xs text-slate-500 mt-1">$<?= number_format($stats['total_paid'], 2) ?> deposits cleared</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                <i class="bi bi-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Providers</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1"><?= esc($stats['total_providers']) ?></h3>
                <p class="text-xs text-amber-600 font-semibold mt-1 flex items-center gap-1">
                    <i class="bi bi-clock-history"></i> <?= esc($stats['pending_payments']) ?> payments pending
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- MAIN GRID: UPCOMING EVENTS & TASKS -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

    <!-- UPCOMING EVENTS TABLE (8 cols) -->
    <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bi bi-calendar3 text-indigo-600 text-lg"></i>
                    <h2 class="text-base font-bold text-slate-900">Upcoming Scheduled Events</h2>
                </div>
                <a href="<?= site_url('events') ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                    View All &rarr;
                </a>
            </div>

            <?php if (empty($upcomingEvents)): ?>
                <div class="text-center py-12 px-4">
                    <i class="bi bi-calendar-x text-4xl text-slate-300 block mb-2"></i>
                    <p class="text-sm font-medium text-slate-500 mb-3">No upcoming events scheduled yet.</p>
                    <a href="<?= site_url('events/new') ?>" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-indigo-600 text-white rounded-lg">Create an Event</a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Event & Client</th>
                                <th class="px-4 py-3">Date & Schedule</th>
                                <th class="px-4 py-3">Venue</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Budget</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php foreach ($upcomingEvents as $evt): ?>
                                <?php
                                    $eventDate = new DateTime($evt['event_date']);
                                    $today = new DateTime('today');
                                    $daysLeft = (int) $today->diff($eventDate)->format('%r%a');

                                    // Status colors
                                    $statusClasses = [
                                        'planning'    => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'confirmed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'completed'   => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'draft'       => 'bg-gray-100 text-gray-700 border-gray-200',
                                    ];
                                    $badgeClass = $statusClasses[$evt['status']] ?? 'bg-slate-100 text-slate-700';
                                ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <a href="<?= site_url('events/' . $evt['id']) ?>" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                            <?= esc($evt['title']) ?>
                                        </a>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                            <span><i class="bi bi-person mr-1"></i><?= esc($evt['client_name']) ?></span>
                                            <span class="inline-block px-1.5 py-0.5 rounded-sm bg-slate-100 text-slate-600 border border-slate-200 capitalize font-medium text-[10px]"><?= esc($evt['event_type']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900"><?= $eventDate->format('M j, Y') ?></div>
                                        <div class="text-xs mt-0.5">
                                            <?php if ($daysLeft === 0): ?>
                                                <span class="font-bold text-rose-600">Today!</span>
                                            <?php elseif ($daysLeft > 0): ?>
                                                <span class="font-semibold text-indigo-600">In <?= $daysLeft ?> days</span>
                                            <?php else: ?>
                                                <span class="text-slate-400"><?= abs($daysLeft) ?> days ago</span>
                                            <?php endif; ?>
                                            <?php if ($evt['start_time']): ?>
                                                <span class="text-slate-400">&bull; <?= substr($evt['start_time'], 0, 5) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="truncate max-w-[150px] text-xs text-slate-600" title="<?= esc($evt['location_venue'] ?? 'TBD') ?>">
                                            <i class="bi bi-geo-alt text-slate-400 mr-1"></i><?= esc($evt['location_venue'] ?? 'TBD') ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border <?= $badgeClass ?> capitalize">
                                            <?= esc(str_replace('_', ' ', $evt['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap font-semibold text-slate-900">
                                        $<?= number_format($evt['budget'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="<?= site_url('events/' . $evt['id']) ?>"
                                           class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition shadow-2xs">
                                            Manage <i class="bi bi-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PENDING CHECKLIST TASKS (4 cols) -->
    <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check2-square text-emerald-600 text-lg"></i>
                    <h2 class="text-base font-bold text-slate-900">Pending Tasks</h2>
                </div>
                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-slate-100 text-slate-600">
                    <?= count($pendingTasks) ?> pending
                </span>
            </div>

            <div class="p-5">
                <?php if (empty($pendingTasks)): ?>
                    <div class="text-center py-8">
                        <i class="bi bi-check-circle-fill text-3xl text-emerald-400 block mb-2"></i>
                        <p class="text-sm font-medium text-slate-600">All checklist milestones are complete!</p>
                    </div>
                <?php else: ?>
                    <ul class="divide-y divide-slate-100 space-y-3">
                        <?php foreach ($pendingTasks as $task): ?>
                            <li class="pt-3 first:pt-0">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox"
                                           onchange="toggleTaskStatus(<?= $task['id'] ?>, this)"
                                           <?= $task['status'] === 'completed' ? 'checked' : '' ?>
                                           class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                    <div class="flex-grow">
                                        <p id="task-label-<?= $task['id'] ?>"
                                           class="text-sm font-medium text-slate-800 leading-snug <?= $task['status'] === 'completed' ? 'line-through text-slate-400' : '' ?>">
                                            <?= esc($task['title']) ?>
                                        </p>
                                        <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500">
                                            <a href="<?= site_url('events/' . $task['event_id']) ?>" class="text-indigo-600 hover:text-indigo-800 font-medium truncate max-w-[140px]">
                                                <i class="bi bi-link-45deg"></i><?= esc($task['event_title']) ?>
                                            </a>
                                            <?php if ($task['due_date']): ?>
                                                <span>&bull; Due <?= date('M j', strtotime($task['due_date'])) ?></span>
                                            <?php endif; ?>
                                            <?php if ($task['priority'] === 'high'): ?>
                                                <span class="px-1.5 py-0.2 rounded bg-rose-50 text-rose-600 font-bold text-[10px]">High</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM SECTION: PREFERRED PROVIDERS & CATEGORIES -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- PREFERRED PROVIDERS (8 cols) -->
    <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-award text-amber-500 text-lg"></i>
                <h2 class="text-base font-bold text-slate-900">Preferred Service Providers</h2>
            </div>
            <a href="<?= site_url('providers') ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                View All Vendors &rarr;
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($topProviders as $prov): ?>
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-xs transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs">
                                    <i class="bi <?= esc($prov['category_icon'] ?? 'bi-tag') ?> text-indigo-600"></i>
                                    <?= esc($prov['category_name'] ?? 'General') ?>
                                </span>
                                <span class="text-amber-500 text-xs font-bold tracking-widest">
                                    <?= str_repeat('★', $prov['rating'] ?? 5) ?>
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-900 text-base">
                                <a href="<?= site_url('providers/' . $prov['id']) ?>" class="hover:text-indigo-600 transition">
                                    <?= esc($prov['name']) ?>
                                </a>
                            </h3>

                            <?php if ($prov['contact_person']): ?>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <i class="bi bi-person mr-1"></i><?= esc($prov['contact_person']) ?>
                                </p>
                            <?php endif; ?>

                            <p class="text-xs text-slate-600 mt-2 line-clamp-2">
                                <?= esc($prov['notes'] ?? 'Available for weddings, corporate galas, and private events.') ?>
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-200/80 text-xs">
                            <span class="font-extrabold text-emerald-600"><?= esc($prov['price_range'] ?? '$$') ?> Tier</span>
                            <div class="flex items-center gap-3">
                                <?php if ($prov['phone']): ?>
                                    <a href="tel:<?= esc($prov['phone']) ?>" class="text-slate-400 hover:text-indigo-600 text-sm" title="Call"><i class="bi bi-telephone"></i></a>
                                <?php endif; ?>
                                <?php if ($prov['email']): ?>
                                    <a href="mailto:<?= esc($prov['email']) ?>" class="text-slate-400 hover:text-indigo-600 text-sm" title="Email"><i class="bi bi-envelope"></i></a>
                                <?php endif; ?>
                                <a href="<?= site_url('providers/' . $prov['id']) ?>" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                    Profile &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- SERVICE CATEGORIES (4 cols) -->
    <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-grid text-indigo-600 text-lg"></i>
                <h2 class="text-base font-bold text-slate-900">Service Categories</h2>
            </div>
            <a href="<?= site_url('categories') ?>" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                Manage
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= site_url('providers?category_id=' . $cat['id']) ?>"
                   class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center transition">
                            <i class="bi <?= esc($cat['icon']) ?>"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition">
                            <?= esc($cat['name']) ?>
                        </span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                        <?= esc($cat['provider_count']) ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
