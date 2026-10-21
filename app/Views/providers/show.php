<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- BREADCRUMB & TOP ACTIONS -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('providers') ?>">Providers</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($provider['name']) ?></li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="<?= site_url('providers/' . $provider['id'] . '/edit') ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-pencil"></i> Edit Profile
        </a>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProviderModal">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

<!-- PROVIDER PROFILE CARD -->
<div class="card mb-4 border-0 shadow-sm overflow-hidden">
    <div class="card-body p-4 bg-white">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="category-pill">
                        <i class="bi <?= esc($provider['category_icon'] ?? 'bi-tag') ?> text-primary"></i>
                        <?= esc($provider['category_name'] ?? 'General Vendor') ?>
                    </span>
                    <span class="badge bg-warning text-dark">
                        <?= str_repeat('★', $provider['rating'] ?? 5) ?> (<?= $provider['rating'] ?>/5)
                    </span>
                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-1"><?= esc($provider['price_range']) ?> Price Tier</span>
                    <?php if ($provider['status'] === 'preferred'): ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                            <i class="bi bi-award-fill me-1"></i> Preferred Partner
                        </span>
                    <?php endif; ?>
                </div>

                <h2 class="h3 fw-bold text-dark mb-2"><?= esc($provider['name']) ?></h2>

                <div class="d-flex flex-wrap gap-4 text-muted small mt-3">
                    <?php if ($provider['contact_person']): ?>
                        <div>
                            <i class="bi bi-person-fill text-primary me-1"></i>
                            <strong class="text-dark"><?= esc($provider['contact_person']) ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if ($provider['city']): ?>
                        <div>
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            <?= esc($provider['city']) ?><?= $provider['address'] ? ' (' . esc($provider['address']) . ')' : '' ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($provider['notes'])): ?>
                    <div class="mt-3 text-secondary border-start border-3 border-primary ps-3 py-1 small">
                        <strong>Vendor Capabilities:</strong><br>
                        <?= nl2br(esc($provider['notes'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- DIRECT CONTACT ACTIONS -->
            <div class="col-lg-4">
                <div class="bg-light p-3 rounded border">
                    <h6 class="fw-bold small text-muted text-uppercase mb-3">
                        <i class="bi bi-chat-dots-fill text-primary me-1"></i> Quick Connect
                    </h6>
                    <div class="d-grid gap-2">
                        <?php if ($provider['phone']): ?>
                            <a href="tel:<?= esc($provider['phone']) ?>" class="btn btn-outline-primary btn-sm text-start d-flex align-items-center gap-2">
                                <i class="bi bi-telephone-fill"></i>
                                <span>Call: <?= esc($provider['phone']) ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if ($provider['email']): ?>
                            <a href="mailto:<?= esc($provider['email']) ?>" class="btn btn-outline-secondary btn-sm text-start d-flex align-items-center gap-2">
                                <i class="bi bi-envelope-fill"></i>
                                <span class="text-truncate">Email: <?= esc($provider['email']) ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if ($provider['website']): ?>
                            <a href="<?= esc($provider['website']) ?>" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm text-start d-flex align-items-center gap-2">
                                <i class="bi bi-globe"></i>
                                <span class="text-truncate">Visit Official Website</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOKED EVENTS HISTORY -->
<?php
    $totalContracted = 0;
    $totalReceived = 0;
    foreach ($bookedEvents as $b) {
        $totalContracted += (float) $b['agreed_cost'];
        $totalReceived += (float) $b['deposit_paid'];
    }
?>
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar-check text-primary fs-5"></i>
            <h5 class="card-title mb-0 fw-bold">Event Bookings History</h5>
            <span class="badge bg-primary-subtle text-primary ms-1"><?= count($bookedEvents) ?> events</span>
        </div>
        <div class="small text-muted">
            Total Contracted: <strong class="text-dark">$<?= number_format($totalContracted, 2) ?></strong>
            &bull; Paid: <strong class="text-success">$<?= number_format($totalReceived, 2) ?></strong>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($bookedEvents)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                <p class="mb-2">This provider hasn't been booked for any events yet.</p>
                <a href="<?= site_url('events') ?>" class="btn btn-sm btn-primary">Go to Events to Assign</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Event & Client</th>
                            <th>Event Date</th>
                            <th>Service Description</th>
                            <th>Agreed Cost</th>
                            <th>Deposit Paid</th>
                            <th>Payment Status</th>
                            <th>Contract</th>
                            <th class="text-end">Event Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookedEvents as $bev): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('events/' . $bev['event_id']) ?>" class="fw-bold text-dark text-decoration-none">
                                        <?= esc($bev['event_title']) ?>
                                    </a>
                                    <div class="small text-muted">
                                        <i class="bi bi-person me-1"></i><?= esc($bev['client_name']) ?>
                                        <span class="badge bg-light text-secondary border ms-1 text-capitalize"><?= esc($bev['event_type']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <?= date('M j, Y', strtotime($bev['event_date'])) ?>
                                    </div>
                                    <span class="badge-status status-<?= esc($bev['event_status']) ?> text-capitalize small">
                                        <?= esc(str_replace('_', ' ', $bev['event_status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium"><?= esc($bev['service_description'] ?? 'Event Services') ?></div>
                                    <?php if (!empty($bev['notes'])): ?>
                                        <div class="small text-muted text-truncate" style="max-width: 200px;" title="<?= esc($bev['notes']) ?>"><?= esc($bev['notes']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-dark">
                                    $<?= number_format($bev['agreed_cost'], 2) ?>
                                </td>
                                <td class="fw-semibold text-success">
                                    $<?= number_format($bev['deposit_paid'], 2) ?>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= esc($bev['payment_status']) ?> text-capitalize">
                                        <?= esc($bev['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border text-capitalize">
                                        <?= esc($bev['contract_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url('events/' . $bev['event_id']) ?>" class="btn btn-sm btn-outline-primary">
                                        View Event &rarr;
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

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Delete Provider?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete <strong><?= esc($provider['name']) ?></strong>?</p>
                <p class="text-muted small mb-0">This vendor will be removed from all future searches and assignments.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="<?= site_url('providers/' . $provider['id'] . '/delete') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Yes, Delete Provider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
