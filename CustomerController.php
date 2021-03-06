<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;


class CustomerController extends Controller
{

    public function index()
    {

        return response()
            ->json([
                'model' => Customer::FilterPaginatedOrder(),
            ]);


    }

    public function create()
    {
        return response()
            ->json([
                'form'   => Customer::initialize(),
                'option' => []
            ]);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required',
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'address' => 'required'
        ]);

     $customer = Customer::create($request->all());

        return response()
            ->json([
                'saved' => true,
            ]);
    }

    public function show($id)
    {

        $customer = Customer::findOrfail($id);

        return response()
            ->json([
                'model' => $customer,
            ]);
    }

    public function edit($id)
    {

        $customer = Customer::findOrfail($id);

        return response()
            ->json([
                'form'   => $customer,
                'option' => [],
            ]);
    }

    public function update(Request $request, $id)
    {


        $this->validate($request, [
            'company' => 'required',
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'address' => 'required',
        ]);

        $customer = Customer::findOrfail($id);
        $customer->update($request->all());

        return response()
            ->json([
                'saved' => true,
            ]);
    }

    public function destroy($id)
    {

        $customer = Customer::findOrfail($id);

        $customer->delete();

        return response()
            ->json([
                'deleted' => true,
            ]);

    }
}
