<?php
class SalesController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Sales',
            'sales'     => $this->model('Sale')->allWithSeller(),
            'employees' => $this->model('Employee')->activeEmployees(),
            'nextInvoice' => $this->model('Sale')->generateInvoiceNo(),
        ];
        $this->view('sales/index', $data);
    }

    public function receipt($id)
    {
        if (!ctype_digit((string) $id) || (int) $id < 1) {
            http_response_code(404);
            $this->bareView('sales/receipt', ['sale' => null]);
            return;
        }

        $sale = $this->model('Sale')->receiptById((int) $id);
        if (!$sale) {
            http_response_code(404);
        }

        $this->bareView('sales/receipt', ['sale' => $sale]);
    }

    public function store()
    {
        if ($this->isPost()) {
            $qty = (float) $this->input('quantity', 0);
            $price = (float) $this->input('unit_price', 0);
            $this->model('Sale')->insert([
                'invoice_no'       => $this->input('invoice_no'),
                'customer_name'    => $this->input('customer_name'),
                'customer_contact' => $this->input('customer_contact'),
                'product_name'     => $this->input('product_name'),
                'quantity'         => $qty,
                'unit'             => $this->input('unit', 'kg'),
                'unit_price'       => $price,
                'total_amount'     => $qty * $price,
                'sale_date'        => $this->input('sale_date'),
                'payment_status'   => $this->input('payment_status', 'Paid'),
                'sold_by'          => $this->input('sold_by') ?: null,
            ]);
            $this->setFlash('success', 'Sale recorded successfully.');
        }
        $this->redirect('sales');
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $qty = (float) $this->input('quantity', 0);
            $price = (float) $this->input('unit_price', 0);
            $this->model('Sale')->update($id, [
                'customer_name'    => $this->input('customer_name'),
                'customer_contact' => $this->input('customer_contact'),
                'product_name'     => $this->input('product_name'),
                'quantity'         => $qty,
                'unit'             => $this->input('unit'),
                'unit_price'       => $price,
                'total_amount'     => $qty * $price,
                'sale_date'        => $this->input('sale_date'),
                'payment_status'   => $this->input('payment_status'),
                'sold_by'          => $this->input('sold_by') ?: null,
            ]);
            $this->setFlash('success', 'Sale updated.');
        }
        $this->redirect('sales');
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            $this->model('Sale')->delete($id);
            $this->setFlash('success', 'Sale deleted.');
        }
        $this->redirect('sales');
    }
}
