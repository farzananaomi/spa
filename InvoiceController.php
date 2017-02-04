<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Customer;
use App\InvoiceItem;

class InvoiceController extends Controller
{
    public function index()
    {

        return response()
            ->json([
                'model' => Invoice::with('customer')->FilterPaginatedOrder(),
            ]);


    }

    public function create()
    {
        return response()
            ->json([
                'form'   => Invoice::initialize(),
                'option' => [
                    'customers' => Customer::OrderBy('name')->get(),
                ],
            ]);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id'         => 'required|exists:customers,id',
            'title'               => 'required',
            'date'                => 'required|date_format:y-m-d',
            'due_date'            => 'required|date_format:y-m-d',
            'discount'            => 'required|numeric|min:0',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|max:255',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $data              = $request->except('items');
        $data{'sub_total'} = 0;
        $items             = [];

        foreach ($request->items as $item) {
            $data['sub_total'] += $item['unit_price'] * $item['quantity'];
            $item[] = new InvoiceItem($item);
        }

        $data['total'] = $data['sub_total'] - $data['discount'];
        $invoice       = Invoice::create($data);

        $invoice->items()
                ->SaveMany($items);

        return response()
            ->json([
                'saved' => true,
            ]);

    }

    public function show($id)
    {

        $invoice = Invoice::with('customer', 'items')->findOrfail($id);

        return response()
            ->json([
                'model' => $invoice,
            ]);
    }

    public function edit($id)
    {

        $invoice = Invoice::with('items')->findOrfail($id);

        return response()
            ->json([
                'form'   => $invoice,
                'option' => Customer::OrderBy('name')->get(),

            ]);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'customer_id'         => 'required|exists:customers,id',
            'title'               => 'required',
            'date'                => 'required|date_format:y-m-d',
            'due_date'            => 'required|date_format:y-m-d',
            'discount'            => 'required|numeric|min:0',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|max:255',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::with('items')->findOrfail($id);

        $data              = $request->except('items');
        $data{'sub_total'} = 0;
        $items             = [];
        $itemIds           = [];

        foreach ($request->items as $item) {
            $data['sub_total'] += $item['unit_price'] * $item['quantity'];
            if (isset($item['id'])) {

                InvoiceItem::whereId($item['id'])
                           ->whereInvoiceId($invoice->id)
                           ->update($item);
            } else {
                $items[] = new InvoiceItem($items);
            }
        }


        $data['total'] = $data['sub_total'] - $data['discount'];
        $invoice       = update($data);

        if (count($itemIds)) {

            InvoiceItem::whereInvoiceId($invoice->id)
                       ->whereNotId('$id', $itemIds)
                       ->delete();
        }

        if (count($items)) {

            $invoice->items()
                    ->SaveMany($items);
        }

        return response()
            ->json([
                'saved' => true,
            ]);

    }

    public function destroy($id)
    {

        $invoice = Invoice::findOrfail($id);

        InvoiceItem::whereInvoiceId($invoice->id)
                   ->delete();
                    $invoice->delete();

        return response()
            ->json([
                'deleted' => true,
            ]);

    }
}
