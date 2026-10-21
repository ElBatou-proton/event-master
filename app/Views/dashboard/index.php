<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="h3 fw-bold mb-1">Event Planning Dashboard</h2>
        <p class="text-muted mb-0">Overview of upcoming events, service provider commitments, and tasks.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('events/new') ?>" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-calendar-plus"></i>
            <span>Create Event</span>
        </a>
        <a href="<?= site_url('providers/new') ?>" class="btn btn-outline-primary d-inline-flex align-items-center gap-1">
            <i class="bi bi-person-plus"></i>
            <span>Register Provider</span>
        </a>
    </div>
</div>

<!-- TOP METRIC CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card metric-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Upcoming Events</span>
                    <h3 class="fw-bold my-1 text-dark"><?= esc($stats['upcoming_events']) ?></h3>
                    <span class="small text-muted"><?= esc($stats['total_events']) ?> total events</span>
                </div>
                <div class="icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card metric-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Event Budget</span>
                    <h3 class="fw-bold my-1 text-dark">$<?= number_format($stats['total_budget'], 2) ?></h3>
                    <span class="small text-success fw-semibold">
                        <i class="bi bi-check-circle me-1"></i>Active budgets
                    </span>
                </div>
                <div class="icon-box bg-success-subtle text-success">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card metric-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Committed Costs</span>
                    <h3 class="fw-bold my-1 text-dark">$<?= number_format($stats['total_committed'], 2) ?></h3>
                    <span class="small text-muted">$<?= number_format($stats['total_paid'], 2) ?> deposits paid</span>
                </div>
                <div class="icon-box bg-info-subtle text-info">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card metric-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Active Providers</span>
                    <h3 class="fw-bold my-1 text-dark"><?= esc($stats['total_providers']) ?></h3>
                    <span class="small text-warning fw-semibold">
                        <i class="bi bi-clock-history me-1"></i><?= esc($stats['pending_payments']) ?> payments pending
                    </span>
                </div>
                <div class="icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN GRID: UPCOMING EVENTS & TASKS -->
<div class="row g-4 mb-4">
    <!-- UPCOMING EVENTS LIST -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3 text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Upcoming Scheduled Events</h5>
                </div>
                <a href="<?= site_url('events') ?>" class="btn btn-sm btn-outline-secondary">View All Events &rarr;</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($upcomingEvents)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        <p class="mb-2">No upcoming events scheduled yet.</p>
                        <a href="<?= site_url('events/new') ?>" class="btn btn-sm btn-primary">Create an Event</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Event & Client</th>
                                    <th>Date & Time</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th>Budget</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingEvents as $evt): ?>
                                    <?php
                                        $eventDate = new DateTime($evt['event_date']);
                                        $today = new DateTime('today');
                                        $daysLeft = (int) $today->diff($eventDate)->format('%r%a');
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= site_url('events/' . $evt['id']) ?>" class="fw-bold text-decoration-none text-dark">
                                                <?= esc($evt['title']) ?>
                                            </a>
                                            <div class="small text-muted">
                                                <i class="bi bi-person me-1"></i><?= esc($evt['client_name']) ?>
                                                <span class="badge bg-light text-secondary border ms-1 text-capitalize"><?= esc($evt['event_type']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?= $eventDate->format('M j, Y') ?></div>
                                            <div class="small">
                                                <?php if ($daysLeft === 0): ?>
                                                    <span class="badge bg-danger">Today!</span>
                                                <?php elseif ($daysLeft > 0): ?>
                                                    <span class="text-primary fw-semibold">In <?= $daysLeft ?> days</span>
                                                <?php else: ?>
                                                    <span class="text-muted"><?= abs($daysLeft) ?> days ago</span>
                                                <?php endif; ?>
                                                <?php if ($evt['start_time']): ?>
                                                    <span class="text-muted ms-1">&bull; <?= substr($evt['start_time'], 0, 5) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-truncate" style="max-width: 160px;" title="<?= esc($evt['location_venue'] ?? 'TBD') ?>">
                                                <i class="bi bi-geo-alt text-muted me-1"></i><?= esc($evt['location_venue'] ?? 'TBD') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-status status-<?= esc($evt['status']) ?> text-capitalize">
                                                <?= esc(str_replace('_', ' ', $evt['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="fw-semibold">
                                            $<?= number_format($evt['budget'], 2) ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= site_url('events/' . $evt['id']) ?>" class="btn btn-sm btn-light border" title="Manage Event">
                                                Manage <i class="bi bi-arrow-right ms-1"></i>
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
    </div>

    <!-- PENDING TASKS CHECKLIST -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check2-square text-success fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Pending Tasks</h5>
                </div>
                <span class="badge bg-secondary-subtle text-secondary"><?= count($pendingTasks) ?> pending</span>
            </div>
            <div class="card-body p-3">
                <?php if (empty($pendingTasks)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-emoji-smile fs-2 d-block mb-2 text-success"></i>
                        <p class="mb-0">All checklist tasks are completed!</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($pendingTasks as $task): ?>
                            <li class="list-group-item px-0 py-2 border-bottom">
                                <div class="d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="checkbox"
                                           onchange="toggleTaskStatus(<?= $task['id'] ?>, this)"
                                           <?= $task['status'] === 'completed' ? 'checked' : '' ?>>
                                    <div class="flex-grow-1">
                                        <div id="task-label-<?= $task['id'] ?>" class="fw-medium text-dark small <?= $task['status'] === 'completed' ? 'text-decoration-line-through text-muted' : '' ?>">
                                            <?= esc($task['title']) ?>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2 mt-1 small">
                                            <a href="<?= site_url('events/' . $task['event_id']) ?>" class="text-primary text-decoration-none">
                                                <i class="bi bi-link-45deg"></i><?= esc($task['event_title']) ?>
                                            </a>
                                            <?php if ($task['due_date']): ?>
                                                <span class="text-muted">&bull; Due <?= date('M j', strtotime($task['due_date'])) ?></span>
                                            <?php endif; ?>
                                            <?php if ($task['priority'] === 'high'): ?>
                                                <span class="badge bg-danger-subtle text-danger">High Priority</span>
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

<!-- PROVIDERS & CATEGORIES SECTION -->
<div class="row g-4">
    <!-- FEATURED PROVIDERS -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-award text-warning fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Preferred Service Providers</h5>
                </div>
                <a href="<?= site_url('providers') ?>" class="btn btn-sm btn-outline-secondary">View All Providers &rarr;</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($topProviders as $prov): ?>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="category-pill">
                                            <i class="bi <?= esc($prov['category_icon'] ?? 'bi-tag') ?> text-primary"></i>
                                            <?= esc($prov['category_name'] ?? 'General') ?>
                                        </span>
                                        <span class="badge bg-warning text-dark">
                                            <?= str_repeat('★', $prov['rating'] ?? 5) ?>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="<?= site_url('providers/' . $prov['id']) ?>" class="text-dark text-decoration-none">
                                            <?= esc($prov['name']) ?>
                                        </a>
                                    </h6>
                                    <?php if ($prov['contact_person']): ?>
                                        <div class="small text-muted mb-2">
                                            <i class="bi bi-person me-1"></i><?= esc($prov['contact_person']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="small text-secondary mb-2 text-truncate">
                                        <?= esc($prov['notes'] ?? 'Available for bookings.') ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 small">
                                    <span class="fw-bold text-success"><?= esc($prov['price_range'] ?? '$$') ?></span>
                                    <div class="d-flex gap-2">
                                        <?php if ($prov['phone']): ?>
                                            <a href="tel:<?= esc($prov['phone']) ?>" class="text-secondary" title="Call"><i class="bi bi-telephone"></i></a>
                                        <?php endif; ?>
                                        <?php if ($prov['email']): ?>
                                            <a href="mailto:<?= esc($prov['email']) ?>" class="text-secondary" title="Email"><i class="bi bi-envelope"></i></a>
                                        <?php endif; ?>
                                        <a href="<?= site_url('providers/' . $prov['id']) ?>" class="text-primary fw-semibold">Profile &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICE CATEGORIES -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-tags text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Provider Categories</h5>
                </div>
                <a href="<?= site_url('categories') ?>" class="btn btn-sm btn-outline-secondary">Manage</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= site_url('providers?category_id=' . $cat['id']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-box bg-light text-primary" style="width: 32px; height: 32px; font-size: 1rem; border-radius: 6px;">
                                    <i class="bi <?= esc($cat['icon']) ?>"></i>
                                </span>
                                <span class="fw-semibold text-dark small"><?= esc($cat['name']) ?></span>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                <?= esc($cat['provider_count']) ?> vendors
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
