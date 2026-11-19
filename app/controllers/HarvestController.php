<?php
class HarvestController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Harvest',
            'harvests'  => $this->model('Harvest')->allWithDetails(),
            'batches'   => $this->model('Production')->activeForDropdown(),
            'allBatches'=> $this->model('Production')->allForDropdown(),
            'employees' => $this->model('Employee')->activeEmployees(),
        ];
        $this->view('harvest/index', $data);
    }

    public function store()
    {
        if ($this->isPost()) {
            $this->model('Harvest')->insert([
                'production_id' => (int) $this->input('production_id'),
                'harvest_date'  => $this->input('harvest_date'),
                'weight_kg'     => (float) $this->input('weight_kg', 0),
                'grade'         => $this->input('grade', 'A'),
                'harvested_by'  => $this->input('harvested_by') ?: null,
                'notes'         => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Harvest record added.');
        }
        $this->redirect('harvest');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $productionId = (int) $this->input('production_id', 0);
            $weight = (float) $this->input('weight_kg', -1);
            if (!ctype_digit((string) $id) || (int) $id < 1) {
                $this->setFlash('danger', 'Invalid harvest record.');
                $this->redirect('harvest');
            }
            if ($productionId < 1) {
                $this->setFlash('danger', 'Please select a production batch.');
                $this->redirect('harvest');
            }
            if ($weight < 0) {
                $this->setFlash('danger', 'Harvest weight cannot be negative.');
                $this->redirect('harvest');
            }
            $this->model('Harvest')->update($id, [
                'production_id' => $productionId,
                'harvest_date' => $this->input('harvest_date'),
                'weight_kg'    => $weight,
                'grade'        => $this->input('grade'),
                'harvested_by' => $this->input('harvested_by') ?: null,
                'notes'        => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Harvest record updated.');
        }
        $this->redirect('harvest');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Harvest')->delete($id);
            $this->setFlash('success', 'Harvest record deleted.');
        }
        $this->redirect('harvest');
    }
}
