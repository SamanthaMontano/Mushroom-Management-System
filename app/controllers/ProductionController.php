<?php
class ProductionController extends Controller
{
    public function index()
    {
        $model = $this->model('Production');
        $employeeModel = $this->model('Employee');

        $data = [
            'pageTitle'   => 'Production',
            'batches'     => $model->allWithEmployee(),
            'employees'   => $employeeModel->activeEmployees(),
            'nextCode'    => $model->generateBatchCode(),
        ];
        $this->view('production/index', $data);
    }

    public function store()
    {
        if ($this->isPost()) {
            $model = $this->model('Production');
            $model->insert([
                'batch_code'             => $this->input('batch_code'),
                'mushroom_variety'       => $this->input('mushroom_variety'),
                'substrate_type'         => $this->input('substrate_type'),
                'bag_quantity'           => (int) $this->input('bag_quantity', 0),
                'inoculation_date'       => $this->input('inoculation_date'),
                'expected_harvest_date'  => $this->input('expected_harvest_date') ?: null,
                'growing_location'       => $this->input('growing_location'),
                'responsible_employee_id'=> $this->input('responsible_employee_id') ?: null,
                'status'                 => $this->input('status', 'Incubation'),
                'notes'                  => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Production batch added successfully.');
        }
        $this->redirect('production');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $model = $this->model('Production');
            $model->update($id, [
                'mushroom_variety'       => $this->input('mushroom_variety'),
                'substrate_type'         => $this->input('substrate_type'),
                'bag_quantity'           => (int) $this->input('bag_quantity', 0),
                'inoculation_date'       => $this->input('inoculation_date'),
                'expected_harvest_date'  => $this->input('expected_harvest_date') ?: null,
                'growing_location'       => $this->input('growing_location'),
                'responsible_employee_id'=> $this->input('responsible_employee_id') ?: null,
                'status'                 => $this->input('status'),
                'notes'                  => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Production batch updated.');
        }
        $this->redirect('production');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Production')->delete($id);
            $this->setFlash('success', 'Production batch deleted.');
        }
        $this->redirect('production');
    }
}
