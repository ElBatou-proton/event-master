<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="h3 fw-bold mb-1">Events Directory</h2>
        <p class="text-muted mb-0">Track all events, budgets, schedules, and assigned providers.</p>
    </div>
    <div>
        <a href="<?= site_url('events/new') ?>" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-calendar-plus"></i>
            <span>Create New Event</span>
        </a>
    </div>
</div>

<!-- FILTERS CARD -->
<div class="card mb-4 p-3 bg-white">
    <form method="get" action="<?= site_url('events') ?>" class="row g-2 align-items-center">
        <!-- Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search event, client, venue..." value="<?= esc($search ?? '') ?>">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-sm-6 col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="planning" <?= ($status ?? '') === 'planning' ? 'selected' : '' ?>>Planning</option>
                <option value="confirmed" <?= ($status ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <!-- Type Filter -->
        <div class="col-sm-6 col-md-3">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">All Event Types</option>
                <option value="wedding" <?= ($type ?? '') === 'wedding' ? 'selected' : '' ?>>Wedding</option>
                <option value="corporate" <?= ($type ?? '') === 'corporate' ? 'selected' : '' ?>>Corporate</option>
                <option value="birthday" <?= ($type ?? '') === 'birthday' ? 'selected' : '' ?>>Birthday</option>
                <option value="conference" <?= ($type ?? '') === 'conference' ? 'selected' : '' ?>>Conference</option>
                <option value="concert" <?= ($type ?? '') === 'concert' ? 'selected' : '' ?>>Concert</option>
                <option value="party" <?= ($type ?? '') === 'party' ? 'selected' : '' ?>>Party</option>
                <option value="anniversary" <?= ($type ?? '') === 'anniversary' ? 'selected' : '' ?>>Anniversary</option>
                <option value="other" <?= ($type ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <?php if (!empty($search) || !empty($status) || !empty($type)): ?>
                <a href="<?= site_url('events') ?>" class="btn btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- EVENTS TABLE / LIST -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($events)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                <h5 class="fw-semibold">No events found</h5>
                <p class="text-muted mb-3">Try adjusting your filters or create a new event.</p>
                <a href="<?= site_url('events/new') ?>" class="btn btn-primary">Create Event</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 240px;">Event & Client</th>
                            <th>Date & Schedule</th>
                            <th>Location / Venue</th>
                            <th>Status</th>
                            <th style="min-width: 180px;">Budget & Spend</th>
                            <th>Vendors</th>
                            <th class="text-end" style="min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $evt): ?>
                            <?php
                                $percentUsed = $evt['budget'] > 0 ? round(($evt['total_provider_cost'] / $evt['budget']) * 100) : 0;
                                $isOverBudget = $evt['total_provider_cost'] > $evt['budget'] && $evt['budget'] > 0;
                                $barClass = $isOverBudget ? 'bg-danger' : ($percentUsed > 80 ? 'bg-warning' : 'bg-primary');
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <a href="<?= site_url('events/' . $evt['id']) ?>" class="fw-bold text-dark text-decoration-none fs-6">
                                                <?= esc($evt['title']) ?>
                                            </a>
                                            <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                                                <span><i class="bi bi-person me-1"></i><?= esc($evt['client_name']) ?></span>
                                                <span class="badge bg-light text-secondary border text-capitalize"><?= esc($evt['event_type']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <?= date('M j, Y', strtotime($evt['event_date'])) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?php if ($evt['start_time']): ?>
                                            <i class="bi bi-clock me-1"></i><?= substr($evt['start_time'], 0, 5) ?>
                                            <?php if ($evt['end_time']): ?> - <?= substr($evt['end_time'], 0, 5) ?><?php endif; ?>
                                        <?php else: ?>
                                            <span>Full Day</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate small" style="max-width: 180px;" title="<?= esc($evt['location_venue'] ?? 'TBD') ?>">
                                        <i class="bi bi-geo-alt text-muted me-1"></i><?= esc($evt['location_venue'] ?? 'Location TBD') ?>
                                    </div>
                                    <?php if ($evt['guest_count'] > 0): ?>
                                        <div class="small text-muted">
                                            <i class="bi bi-people me-1"></i><?= esc($evt['guest_count']) ?> guests
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= esc($evt['status']) ?> text-capitalize">
                                        <?= esc(str_replace('_', ' ', $evt['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Spent: <strong class="<?= $isOverBudget ? 'text-danger' : 'text-dark' ?>">$<?= number_format($evt['total_provider_cost'], 0) ?></strong></span>
                                        <span class="text-muted">Budget: $<?= number_format($evt['budget'], 0) ?></span>
                                    </div>
                                    <div class="progress" style="height: 6px;" title="<?= $percentUsed ?>% utilized">
                                        <div class="progress-bar <?= $barClass ?>" role="progressbar" style="width: <?= min($percentUsed, 100) ?>%"></div>
                                    </div>
                                    <div class="small text-end mt-1 <?= $isOverBudget ? 'text-danger fw-bold' : 'text-muted' ?>">
                                        <?= $percentUsed ?>% allocated
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-people-fill text-primary me-1"></i><?= esc($evt['provider_count']) ?> assigned
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= site_url('events/' . $evt['id']) ?>" class="btn btn-outline-primary" title="View & Manage Event">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= site_url('events/' . $evt['id'] . '/edit') ?>" class="btn btn-outline-secondary" title="Edit Event Details">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete Event" data-bs-toggle="modal" data-bs-target="#deleteModal-<?= $evt['id'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal-<?= $evt['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold text-danger">Delete Event?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong><?= esc($evt['title']) ?></strong>?</p>
                                                    <p class="text-muted small mb-0">This will remove all associated provider assignments and checklist tasks. This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="post" action="<?= site_url('events/' . $evt['id'] . '/delete') ?>">
                                                        <button type="submit" class="btn btn-danger">Yes, Delete Event</button>
                                                    </form>
                                                </div>
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
</div>

<?= $this->endSection() ?>
