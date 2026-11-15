<?php
class ExpenseController extends Controller
{
    public function index()
    {
        $this->requireOwner();
        $data = [
            'pageTitle' => 'Expenses',
            'expenses'  => $this->model('Expense')->allWithEmployee(),
            'employees' => $this->model('Employee')->activeEmployees(),
            'total'     => $this->model('Expense')->totalThisMonth(),
        ];
        $this->view('expenses/index', $data);
    }

    public function store()
    {
        $this->requireOwner();
        if ($this->isPost()) {
            $this->model('Expense')->insert([
                'category'     => $this->input('category'),
                'description'  => $this->input('description'),
                'amount'       => (float) $this->input('amount', 0),
                'expense_date' => $this->input('expense_date'),
                'recorded_by'  => $this->input('recorded_by') ?: null,
            ]);
            $this->setFlash('success', 'Expense recorded.');
        }
        $this->redirect('expense');
    }

    public function update($id)
    {
        $this->requireOwner();
        if ($this->isPost()) {
            $this->model('Expense')->update($id, [
                'category'     => $this->input('category'),
                'description'  => $this->input('description'),
                'amount'       => (float) $this->input('amount', 0),
                'expense_date' => $this->input('expense_date'),
                'recorded_by'  => $this->input('recorded_by') ?: null,
            ]);
            $this->setFlash('success', 'Expense updated.');
        }
        $this->redirect('expense');
    }

    public function delete($id)
    {
        $this->requireOwner();
        if ($this->isPost()) {
            $this->model('Expense')->delete($id);
            $this->setFlash('success', 'Expense deleted.');
        }
        $this->redirect('expense');
    }
}
