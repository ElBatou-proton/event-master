<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- BREADCRUMB & TOP ACTIONS -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('events') ?>">Events</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($event['title']) ?></li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="<?= site_url('events/' . $event['id'] . '/edit') ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-pencil"></i> Edit Details
        </a>
        <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#assignProviderModal">
            <i class="bi bi-person-plus-fill"></i> Assign Provider
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEventModal">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

<!-- EVENT OVERVIEW HERO CARD -->
<div class="card mb-4 border-0 shadow-sm overflow-hidden">
    <div class="card-body p-4 bg-white">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-primary text-uppercase px-2 py-1"><?= esc($event['event_type']) ?></span>
                    <span class="badge-status status-<?= esc($event['status']) ?> text-capitalize">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i><?= esc(str_replace('_', ' ', $event['status'])) ?>
                    </span>
                    <?php
                        $eventDate = new DateTime($event['event_date']);
                        $today = new DateTime('today');
                        $diff = (int) $today->diff($eventDate)->format('%r%a');
                    ?>
                    <?php if ($diff === 0): ?>
                        <span class="badge bg-danger">Event is Today!</span>
                    <?php elseif ($diff > 0): ?>
                        <span class="badge bg-light text-primary border">In <?= $diff ?> days</span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border"><?= abs($diff) ?> days ago</span>
                    <?php endif; ?>
                </div>

                <h2 class="h3 fw-bold text-dark mb-2"><?= esc($event['title']) ?></h2>

                <div class="d-flex flex-wrap gap-4 text-muted small mt-3">
                    <div>
                        <i class="bi bi-calendar3 text-primary me-1"></i>
                        <strong class="text-dark"><?= $eventDate->format('l, F j, Y') ?></strong>
                    </div>
                    <?php if ($event['start_time']): ?>
                        <div>
                            <i class="bi bi-clock text-primary me-1"></i>
                            <?= substr($event['start_time'], 0, 5) ?><?= $event['end_time'] ? ' - ' . substr($event['end_time'], 0, 5) : '' ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <?= esc($event['location_venue'] ?? 'Venue To Be Announced') ?>
                    </div>
                    <div>
                        <i class="bi bi-people-fill text-info me-1"></i>
                        <?= esc($event['guest_count']) ?> Expected Guests
                    </div>
                </div>

                <?php if (!empty($event['description'])): ?>
                    <div class="mt-3 text-secondary border-start border-3 border-primary ps-3 py-1 small">
                        <?= nl2br(esc($event['description'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- CLIENT CONTACT BOX -->
            <div class="col-lg-4">
                <div class="bg-light p-3 rounded border">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">
                        <i class="bi bi-person-badge text-primary me-1"></i> Client Information
                    </h6>
                    <div class="fw-bold text-dark fs-6 mb-1"><?= esc($event['client_name']) ?></div>
                    <?php if ($event['client_email']): ?>
                        <div class="small mb-1">
                            <i class="bi bi-envelope text-muted me-1"></i>
                            <a href="mailto:<?= esc($event['client_email']) ?>" class="text-decoration-none text-dark"><?= esc($event['client_email']) ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if ($event['client_phone']): ?>
                        <div class="small">
                            <i class="bi bi-telephone text-muted me-1"></i>
                            <a href="tel:<?= esc($event['client_phone']) ?>" class="text-decoration-none text-dark"><?= esc($event['client_phone']) ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($event['notes'])): ?>
                        <div class="mt-2 pt-2 border-top small text-muted">
                            <strong>Planner Notes:</strong> <?= esc($event['notes']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FINANCIAL TRACKER & BUDGET OVERVIEW -->
<div class="card mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-cash-stack text-success fs-5"></i>
            <h5 class="card-title mb-0 fw-bold">Financial & Budget Tracker</h5>
        </div>
        <div>
            <?php if ($event['budget_variance'] < 0): ?>
                <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-semibold">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Over Budget by $<?= number_format(abs($event['budget_variance']), 2) ?>
                </span>
            <?php else: ?>
                <span class="badge bg-success-subtle text-success px-3 py-2 fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> Under Budget: $<?= number_format($event['budget_variance'], 2) ?> remaining
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3 text-center">
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded border">
                    <span class="text-muted small text-uppercase fw-semibold">Target Budget</span>
                    <h4 class="fw-bold text-dark my-1">$<?= number_format($event['budget'], 2) ?></h4>
                    <span class="small text-muted">Total set for event</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded border">
                    <span class="text-muted small text-uppercase fw-semibold">Committed Cost</span>
                    <h4 class="fw-bold my-1 <?= $event['budget_variance'] < 0 ? 'text-danger' : 'text-primary' ?>">
                        $<?= number_format($event['total_provider_cost'], 2) ?>
                    </h4>
                    <span class="small text-muted"><?= count($assignedProviders) ?> providers hired</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded border">
                    <span class="text-muted small text-uppercase fw-semibold">Deposits Paid</span>
                    <h4 class="fw-bold text-success my-1">$<?= number_format($event['total_deposit_paid'], 2) ?></h4>
                    <span class="small text-muted">Confirmed payments</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded border">
                    <span class="text-muted small text-uppercase fw-semibold">Unpaid Balance</span>
                    <h4 class="fw-bold text-warning-emphasis my-1">$<?= number_format($event['remaining_balance'], 2) ?></h4>
                    <span class="small text-muted">Due to vendors</span>
                </div>
            </div>
        </div>

        <!-- PROGRESS BAR -->
        <?php
            $budgetPercent = $event['budget'] > 0 ? round(($event['total_provider_cost'] / $event['budget']) * 100) : 0;
            $paidPercent = $event['budget'] > 0 ? round(($event['total_deposit_paid'] / $event['budget']) * 100) : 0;
        ?>
        <div class="d-flex justify-content-between small text-muted mb-1">
            <span>Budget Allocation (<?= $budgetPercent ?>% of $<?= number_format($event['budget'], 2) ?>)</span>
            <span>Deposits Cleared: <?= $paidPercent ?>%</span>
        </div>
        <div class="progress" style="height: 10px;">
            <div class="progress-bar <?= $event['budget_variance'] < 0 ? 'bg-danger' : 'bg-primary' ?>"
                 role="progressbar" style="width: <?= min($budgetPercent, 100) ?>%"></div>
        </div>
    </div>
</div>

<!-- TWO COLUMNS: ASSIGNED PROVIDERS & TASKS -->
<div class="row g-4">
    <!-- ASSIGNED PROVIDERS TABLE -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Assigned Service Providers & Vendors</h5>
                    <span class="badge bg-primary-subtle text-primary ms-1"><?= count($assignedProviders) ?></span>
                </div>
                <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#assignProviderModal">
                    <i class="bi bi-plus-lg"></i> Assign Provider
                </button>
            </div>
            <div class="card-body p-0">
                <?php if (empty($assignedProviders)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                        <p class="mb-2">No service providers assigned to this event yet.</p>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignProviderModal">
                            Assign Catering, Venue, DJ, etc.
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Category & Provider</th>
                                    <th>Service Description</th>
                                    <th>Agreed Cost</th>
                                    <th>Deposit Paid</th>
                                    <th>Balance</th>
                                    <th>Payment</th>
                                    <th>Contract</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignedProviders as $ep): ?>
                                    <?php $balance = (float)$ep['agreed_cost'] - (float)$ep['deposit_paid']; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="icon-box bg-light text-primary" style="width: 34px; height: 34px; font-size: 1rem; border-radius: 6px;">
                                                    <i class="bi <?= esc($ep['category_icon'] ?? 'bi-tag') ?>"></i>
                                                </span>
                                                <div>
                                                    <a href="<?= site_url('providers/' . $ep['provider_id']) ?>" class="fw-bold text-dark text-decoration-none">
                                                        <?= esc($ep['provider_name']) ?>
                                                    </a>
                                                    <div class="small text-muted"><?= esc($ep['category_name'] ?? 'General') ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-dark fw-medium"><?= esc($ep['service_description'] ?? 'Standard service') ?></div>
                                            <?php if (!empty($ep['notes'])): ?>
                                                <div class="small text-muted text-truncate" style="max-width: 200px;" title="<?= esc($ep['notes']) ?>">
                                                    <?= esc($ep['notes']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            $<?= number_format($ep['agreed_cost'], 2) ?>
                                        </td>
                                        <td class="text-success fw-semibold">
                                            $<?= number_format($ep['deposit_paid'], 2) ?>
                                        </td>
                                        <td class="fw-semibold <?= $balance > 0 ? 'text-warning-emphasis' : 'text-muted' ?>">
                                            $<?= number_format($balance, 2) ?>
                                        </td>
                                        <td>
                                            <span class="badge-status status-<?= esc($ep['payment_status']) ?> text-capitalize">
                                                <?= esc($ep['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border text-capitalize">
                                                <i class="bi bi-file-earmark-text me-1"></i><?= esc($ep['contract_status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProviderModal-<?= $ep['id'] ?>" title="Edit Cost & Status">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeProviderModal-<?= $ep['id'] ?>" title="Unassign Provider">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Edit Assignment Modal -->
                                            <div class="modal fade" id="editProviderModal-<?= $ep['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered text-start">
                                                    <div class="modal-content">
                                                        <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers/' . $ep['id']) ?>">
                                                            <?= csrf_field() ?>
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold">Edit Assignment: <?= esc($ep['provider_name']) ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Service Description</label>
                                                                    <input type="text" name="service_description" class="form-control" value="<?= esc($ep['service_description']) ?>">
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-semibold">Agreed Cost ($)</label>
                                                                        <input type="number" step="0.01" min="0" name="agreed_cost" class="form-control" value="<?= esc($ep['agreed_cost']) ?>">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-semibold">Deposit Paid ($)</label>
                                                                        <input type="number" step="0.01" min="0" name="deposit_paid" class="form-control" value="<?= esc($ep['deposit_paid']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-semibold">Payment Status</label>
                                                                        <select name="payment_status" class="form-select">
                                                                            <option value="pending" <?= $ep['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                            <option value="partial" <?= $ep['payment_status'] === 'partial' ? 'selected' : '' ?>>Partial</option>
                                                                            <option value="paid" <?= $ep['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid in Full</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label fw-semibold">Contract Status</label>
                                                                        <select name="contract_status" class="form-select">
                                                                            <option value="draft" <?= $ep['contract_status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                                                            <option value="sent" <?= $ep['contract_status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                                                                            <option value="signed" <?= $ep['contract_status'] === 'signed' ? 'selected' : '' ?>>Signed</option>
                                                                            <option value="completed" <?= $ep['contract_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-semibold">Notes / Deliverables</label>
                                                                    <textarea name="notes" rows="2" class="form-control"><?= esc($ep['notes']) ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove Assignment Modal -->
                                            <div class="modal fade" id="removeProviderModal-<?= $ep['id'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered text-start">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold text-danger">Remove Provider Assignment</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to remove <strong><?= esc($ep['provider_name']) ?></strong> from this event?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers/' . $ep['id'] . '/delete') ?>">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="btn btn-danger">Yes, Remove</button>
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
    </div>

    <!-- EVENT CHECKLIST & TASKS -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle text-success fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Event Checklist & Tasks</h5>
                    <span class="badge bg-success-subtle text-success ms-1">
                        <?= $event['completed_tasks'] ?> / <?= $event['total_tasks'] ?> Done (<?= $event['task_percentage'] ?>%)
                    </span>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="bi bi-plus-lg"></i> Add Task
                </button>
            </div>
            <div class="card-body">
                <?php if ($event['total_tasks'] > 0): ?>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $event['task_percentage'] ?>%"></div>
                    </div>
                <?php endif; ?>

                <?php if (empty($tasks)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-card-checklist fs-2 d-block mb-1"></i>
                        <p class="mb-2">No tasks added to the checklist yet.</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add First Task</button>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($tasks as $task): ?>
                            <div class="list-group-item px-0 py-2 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0 fs-5" type="checkbox"
                                           onchange="toggleTaskStatus(<?= $task['id'] ?>, this)"
                                           <?= $task['status'] === 'completed' ? 'checked' : '' ?>>
                                    <div>
                                        <span id="task-label-<?= $task['id'] ?>" class="fw-medium text-dark <?= $task['status'] === 'completed' ? 'text-decoration-line-through text-muted' : '' ?>">
                                            <?= esc($task['title']) ?>
                                        </span>
                                        <div class="d-flex flex-wrap align-items-center gap-2 small text-muted mt-1">
                                            <?php if ($task['provider_name']): ?>
                                                <span class="badge bg-light text-secondary border">
                                                    <i class="bi bi-person me-1"></i><?= esc($task['provider_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($task['due_date']): ?>
                                                <span><i class="bi bi-calendar-event me-1"></i>Due <?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                            <?php endif; ?>
                                            <?php if ($task['priority'] === 'high'): ?>
                                                <span class="badge bg-danger-subtle text-danger">High Priority</span>
                                            <?php elseif ($task['priority'] === 'medium'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis">Medium</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted">Low</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <form method="post" action="<?= site_url('events/' . $event['id'] . '/tasks/' . $task['id'] . '/delete') ?>" onsubmit="return confirm('Delete this task?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm text-danger p-0" title="Delete Task">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ASSIGN PROVIDER -->
<div class="modal fade" id="assignProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('events/' . $event['id'] . '/providers') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Assign Provider to Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Provider <span class="text-danger">*</span></label>
                        <select name="provider_id" class="form-select" required>
                            <option value="">-- Choose a Provider --</option>
                            <?php foreach ($allProviders as $prov): ?>
                                <option value="<?= $prov['id'] ?>">
                                    <?= esc($prov['name']) ?> (<?= esc($prov['category_name'] ?? 'General') ?>) - <?= esc($prov['price_range']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Don't see your provider? <a href="<?= site_url('providers/new') ?>" target="_blank">Register new provider</a></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Description / Scope</label>
                        <input type="text" name="service_description" class="form-control" placeholder="e.g. 5-hour DJ set & ceremony audio">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Agreed Fee / Cost ($)</label>
                            <input type="number" step="0.01" min="0" name="agreed_cost" class="form-control" placeholder="1500.00" value="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Deposit Paid ($)</label>
                            <input type="number" step="0.01" min="0" name="deposit_paid" class="form-control" placeholder="500.00" value="0.00">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="partial">Partial Deposit</option>
                                <option value="paid">Paid in Full</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Contract Status</label>
                            <select name="contract_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="sent">Contract Sent</option>
                                <option value="signed">Contract Signed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Notes / Special Requests</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Setup time, equipment requests, parking..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Assign Provider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: ADD TASK -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('events/' . $event['id'] . '/tasks') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Checklist Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Confirm final tasting menu" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link to Provider (Optional)</label>
                        <select name="provider_id" class="form-select">
                            <option value="">-- No specific provider --</option>
                            <?php foreach ($assignedProviders as $ep): ?>
                                <option value="<?= $ep['provider_id'] ?>"><?= esc($ep['provider_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: DELETE EVENT -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Delete Event?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete <strong><?= esc($event['title']) ?></strong>?</p>
                <p class="text-muted small mb-0">All associated provider assignments and checklist tasks will also be removed. This cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="<?= site_url('events/' . $event['id'] . '/delete') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Yes, Delete Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
