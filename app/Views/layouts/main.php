<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Event Planner') ?> | CodeIgniter 4</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --body-bg: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0f172a !important;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--card-border);
        }

        .nav-link {
            font-weight: 600;
            color: #475569;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
            background-color: var(--primary-light);
        }

        .card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .badge-status {
            padding: 0.4em 0.75em;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 20px;
        }

        .status-planning { background-color: #e0f2fe; color: #0369a1; }
        .status-confirmed { background-color: #dcfce7; color: #15803d; }
        .status-in_progress { background-color: #fef9c3; color: #854d0e; }
        .status-completed { background-color: #f1f5f9; color: #475569; }
        .status-cancelled { background-color: #fee2e2; color: #b91c1c; }
        .status-draft { background-color: #f3f4f6; color: #4b5563; }

        .status-paid { background-color: #dcfce7; color: #15803d; }
        .status-partial { background-color: #fef9c3; color: #854d0e; }
        .status-pending { background-color: #fee2e2; color: #b91c1c; }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.825rem;
            font-weight: 600;
            color: #334155;
        }

        .metric-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.08);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        footer {
            margin-top: auto;
            border-top: 1px solid var(--card-border);
            background: #ffffff;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
                <span class="icon-box bg-primary text-white" style="width: 36px; height: 36px; font-size: 1.1rem; border-radius: 8px;">
                    <i class="bi bi-calendar-event-fill"></i>
                </span>
                <span>Event<span class="text-primary">Master</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with(uri_string(), 'events') ? 'active' : '' ?>" href="<?= site_url('events') ?>">
                            <i class="bi bi-calendar-check me-1"></i> Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with(uri_string(), 'providers') ? 'active' : '' ?>" href="<?= site_url('providers') ?>">
                            <i class="bi bi-people me-1"></i> Providers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with(uri_string(), 'categories') ? 'active' : '' ?>" href="<?= site_url('categories') ?>">
                            <i class="bi bi-grid me-1"></i> Categories
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?= site_url('events/new') ?>" class="btn btn-primary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-plus-lg"></i>
                        <span>New Event</span>
                    </a>
                    <a href="<?= site_url('providers/new') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-person-plus"></i>
                        <span>New Provider</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="py-4 flex-grow-1">
        <div class="container">

            <!-- FLASH ALERTS -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div><?= session()->getFlashdata('success') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <strong>Please check the following errors:</strong>
                    </div>
                    <ul class="mb-0 ps-4">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- PAGE CONTENT -->
            <?= $this->renderSection('content') ?>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-3">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-dark">EventMaster</span>
                <span class="text-muted">&bull; Powered by CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></span>
            </div>
            <div class="text-muted small">
                <span>Manage Events &bull; Catering &bull; Venues &bull; DJs &bull; AV &bull; Photography</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Enable tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

        // Ajax Task status toggle
        function toggleTaskStatus(taskId, checkboxEl) {
            fetch('<?= site_url('events/tasks/') ?>' + taskId + '/toggle', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const label = document.getElementById('task-label-' + taskId);
                    if (label) {
                        if (data.newStatus === 'completed') {
                            label.classList.add('text-decoration-line-through', 'text-muted');
                        } else {
                            label.classList.remove('text-decoration-line-through', 'text-muted');
                        }
                    }
                }
            })
            .catch(err => console.error('Task toggle error:', err));
        }
    </script>
</body>
</html>
