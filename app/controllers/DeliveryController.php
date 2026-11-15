<?php
class DeliveryController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Delivery',
            'deliveries'=> $this->model('Delivery')->allWithSale(),
            'sales'     => $this->model('Sale')->forDropdown(),
        ];
        $this->view('delivery/index', $data);
    }

    public function store()
    {
        if ($this->isPost()) {
            $this->model('Delivery')->insert([
                'sale_id'          => (int) $this->input('sale_id'),
                'delivery_address' => $this->input('delivery_address'),
                'delivery_date'    => $this->input('delivery_date') ?: null,
                'rider_name'       => $this->input('rider_name'),
                'contact_number'   => $this->input('contact_number'),
                'delivery_status'  => $this->input('delivery_status', 'Pending'),
                'delivery_fee'     => (float) $this->input('delivery_fee', 0),
                'notes'            => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Delivery scheduled.');
        }
        $this->redirect('delivery');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $this->model('Delivery')->update($id, [
                'delivery_address' => $this->input('delivery_address'),
                'delivery_date'    => $this->input('delivery_date') ?: null,
                'rider_name'       => $this->input('rider_name'),
                'contact_number'   => $this->input('contact_number'),
                'delivery_status'  => $this->input('delivery_status'),
                'delivery_fee'     => (float) $this->input('delivery_fee', 0),
                'notes'            => $this->input('notes'),
            ]);
            $this->setFlash('success', 'Delivery updated.');
        }
        $this->redirect('delivery');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Delivery')->delete($id);
            $this->setFlash('success', 'Delivery record deleted.');
        }
        $this->redirect('delivery');
    }
}
