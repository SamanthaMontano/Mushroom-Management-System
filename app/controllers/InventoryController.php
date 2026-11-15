<?php
class InventoryController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Inventory',
            'items'     => $this->model('Inventory')->all('item_name ASC'),
        ];
        $this->view('inventory/index', $data);
    }

    public function store()
    {
        if ($this->isPost()) {
            $qty = (float) $this->input('quantity', 0);
            $reorder = (float) $this->input('reorder_level', 0);
            $this->model('Inventory')->insert([
                'item_name'      => $this->input('item_name'),
                'category'       => $this->input('category'),
                'quantity'       => $qty,
                'unit'           => $this->input('unit', 'kg'),
                'reorder_level'  => $reorder,
                'unit_cost'      => (float) $this->input('unit_cost', 0),
                'supplier'       => $this->input('supplier'),
                'last_restocked' => $this->input('last_restocked') ?: null,
                'status'         => Inventory::deriveStatus($qty, $reorder),
            ]);
            $this->setFlash('success', 'Inventory item added.');
        }
        $this->redirect('inventory');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $qty = (float) $this->input('quantity', 0);
            $reorder = (float) $this->input('reorder_level', 0);
            $this->model('Inventory')->update($id, [
                'item_name'      => $this->input('item_name'),
                'category'       => $this->input('category'),
                'quantity'       => $qty,
                'unit'           => $this->input('unit'),
                'reorder_level'  => $reorder,
                'unit_cost'      => (float) $this->input('unit_cost', 0),
                'supplier'       => $this->input('supplier'),
                'last_restocked' => $this->input('last_restocked') ?: null,
                'status'         => Inventory::deriveStatus($qty, $reorder),
            ]);
            $this->setFlash('success', 'Inventory item updated.');
        }
        $this->redirect('inventory');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Inventory')->delete($id);
            $this->setFlash('success', 'Inventory item deleted.');
        }
        $this->redirect('inventory');
    }
}
