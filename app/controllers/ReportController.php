<?php
class ReportController extends Controller
{
    public function index()
    {
        $this->requireOwner();
        $from = $_GET['from'] ?? date('Y-m-01');
        $to   = $_GET['to'] ?? date('Y-m-d');

        $reportModel = $this->model('Report');

        $sales = $reportModel->salesTotal($from, $to);
        $expenses = $reportModel->expenseTotal($from, $to);

        $data = [
            'pageTitle'        => 'Reports',
            'from'             => $from,
            'to'               => $to,
            'salesTotal'       => $sales,
            'expenseTotal'     => $expenses,
            'netProfit'        => $sales - $expenses,
            'harvestTotal'     => $reportModel->harvestTotal($from, $to),
            'salesByProduct'   => $reportModel->salesByProduct($from, $to),
            'expensesByCategory' => $reportModel->expensesByCategory($from, $to),
            'productionSummary' => $reportModel->productionSummary($from, $to),
        ];

        $this->view('reports/index', $data);
    }
}
