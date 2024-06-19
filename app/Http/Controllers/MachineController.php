<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'brand' =>
                'required',

            'type' =>
                'required',

            'serial_number' =>
                'required',

            'bar' =>
                'required',

            'running' =>
                'required',
        ];

        $message = [
            'brand.required' => 'Field brand Wajib Diisi',
            'type.required' => 'Field type Wajib Diisi',
            'serial_number.required' => 'Field Serial Number Wajib Diisi',
            'bar.required' => 'Field bar Wajib Diisi',
            'running.required' => 'Field running Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $machine = new Machine;
        $machine->id_client = $request->id_client;
        $machine->brand = $request->brand;
        $machine->type = $request->type;
        $machine->serial_number = $request->serial_number;
        $machine->bar = $request->bar;
        $machine->running = $request->running;
        $machineSave = $machine->save();
        if ($machineSave) {
            return redirect('/existing/' . $request->id_client)->with('message', 'data telah ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule = [
            'brand' =>
                'required',

            'type' =>
                'required',

            'serial_number' =>
                'required',

            'bar' =>
                'required',

            'running' =>
                'required',
        ];

        $message = [
            'brand.required' => 'Field brand Wajib Diisi',
            'type.required' => 'Field type Wajib Diisi',
            'serial_number.required' => 'Field Serial Number Wajib Diisi',
            'bar.required' => 'Field bar Wajib Diisi',
            'running.required' => 'Field running Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $machine = Machine::find($id);
        $machine->brand = $request->brand;
        $machine->type = $request->type;
        $machine->serial_number = $request->serial_number;
        $machine->bar = $request->bar;
        $machine->running = $request->running;
        $machineSave = $machine->save();
        if ($machineSave) {
            return redirect('/existing/' . $request->id_client)->with('message', 'data telah ditambahkan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $machine = Machine::find($id);

        $delMachine = $machine->delete();
        if ($delMachine) {
            return 1;
        } else {
            return 0;
        }
    }
    public function storeTechnician(Request $request)
    {

        $rule = [
            'brand' =>
                'required',

            'type' =>
                'required',

            'serial_number' =>
                'required',

            'bar' =>
                'required',

            'running' =>
                'required',
        ];

        $message = [
            'brand.required' => 'Field brand Wajib Diisi',
            'type.required' => 'Field type Wajib Diisi',
            'serial_number.required' => 'Field Serial Number Wajib Diisi',
            'bar.required' => 'Field bar Wajib Diisi',
            'running.required' => 'Field running Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $machine = new Machine;
        $machine->id_client = $request->id_client;
        $machine->brand = $request->brand;
        $machine->type = $request->type;
        $machine->serial_number = $request->serial_number;
        $machine->bar = $request->bar;
        $machine->running = $request->running;
        $machineSave = $machine->save();
        if ($machineSave) {
            return redirect('/service-reports/create')->with('message', 'data telah ditambahkan');
        }
    }
}
