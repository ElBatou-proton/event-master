<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ProviderModel;
use App\Models\CategoryModel;
use App\Models\EventTaskModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $eventModel    = new EventModel();
        $providerModel = new ProviderModel();
        $categoryModel = new CategoryModel();
        $taskModel     = new EventTaskModel();

        $stats           = $eventModel->getOverallStats();
        $upcomingEvents  = $eventModel->getUpcomingEvents(6);
        $pendingTasks    = $taskModel->getPendingTasks(6);
        $categories      = $categoryModel->getCategoriesWithProviderCount();
        $topProviders    = $providerModel->getWithCategory();
        // filter or limit top providers to 6
        $topProviders    = array_slice($topProviders, 0, 6);

        return view('dashboard/index', [
            'title'          => lang('App.dashboard_title'),
            'stats'          => $stats,
            'upcomingEvents' => $upcomingEvents,
            'pendingTasks'   => $pendingTasks,
            'categories'     => $categories,
            'topProviders'   => $topProviders,
        ]);
    }
}
