<?php
class ProcessingController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Processing',
            'records'   => $this->model('Processing')->allWithDetails(),
            'harvests'  => $this->model('Harvest')->forDropdown(),
            'employees' => $this->model('Employee')->activeEmployees(),
        ];
        $this->view('processing/index', $data);
    }

    public function store()
    {
        if ($this->isPost()) {
            $this->model('Processing')->insert([
                'harvest_id'      => (int) $this->input('harvest_id'),
                'process_type'    => $this->input('process_type'),
                'input_weight_kg' => (float) $this->input('input_weight_kg', 0),
                'output_weight_kg'=> (float) $this->input('output_weight_kg', 0),
                'output_unit'     => $this->input('output_unit', 'kg'),
                'process_date'    => $this->input('process_date'),
                'processed_by'    => $this->input('processed_by') ?: null,
                'status'          => $this->input('status', 'Completed'),
                'notes'           => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Processing record added.');
        }
        $this->redirect('processing');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $this->model('Processing')->update($id, [
                'process_type'    => $this->input('process_type'),
                'input_weight_kg' => (float) $this->input('input_weight_kg', 0),
                'output_weight_kg'=> (float) $this->input('output_weight_kg', 0),
                'output_unit'     => $this->input('output_unit', 'kg'),
                'process_date'    => $this->input('process_date'),
                'processed_by'    => $this->input('processed_by') ?: null,
                'status'          => $this->input('status'),
                'notes'           => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Processing record updated.');
        }
        $this->redirect('processing');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Processing')->delete($id);
            $this->setFlash('success', 'Processing record deleted.');
        }
        $this->redirect('processing');
    }
}
