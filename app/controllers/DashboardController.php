<?php
class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = $this->model('Dashboard');
        $isOwner = $this->isOwner();

        $data = [
            'pageTitle'         => 'Dashboard',
            'isOwner'            => $isOwner,
            'activeBatches'     => $dashboard->activeBatches(),
            'totalHarvest'      => $dashboard->totalHarvestThisMonth(),
            'lowStockCount'     => $dashboard->lowStockCount(),
            'pendingDeliveries' => $dashboard->pendingDeliveries(),
            'recentHarvests'    => $dashboard->recentHarvests(),
            'lowStockItems'     => $dashboard->lowStockItems(),
            'harvestByVariety'  => $dashboard->harvestByVariety(),
            'productionOverview' => $dashboard->productionOverview(),
            'upcomingHarvests'   => $dashboard->upcomingHarvests(),
            'recentActivities'   => $dashboard->recentActivities($isOwner),
        ];

        if ($isOwner) {
            $data['salesThisMonth'] = $dashboard->salesThisMonth();
            $data['expensesThisMonth'] = $dashboard->expensesThisMonth();
            $data['activeEmployees'] = $dashboard->activeEmployees();
            $data['salesTrend'] = $dashboard->salesLast6Months();
        }

        $this->view('dashboard/index', $data);
    }
}
