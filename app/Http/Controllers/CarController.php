<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::withTrashed()->orderBy('id', 'DESC')->get();
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $input = $request->all();
        $input['video'] = boolval($input['video']);

        try {
            DB::beginTransaction();
            Car::create($input);
            DB::commit();
            return redirect(route('cars.index'))->with('success', 'Vehículo guardado con éxito');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return redirect(route('cars.index'))->with('danger', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::find($id);

        if(empty($car)) {
            return redirect(route('cars.index'))->with('warning', 'Vehículo no encontrado');
        }

        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, $id)
    {

        $car = Car::find($id);

        if(empty($car)) {
            return redirect(route('cars.index'))->with('warning', 'Vehículo no encontrado');
        }

        $input = $request->all();
        $input['video'] = isset($input['video']) ? boolval($input['video']) : false;

        try {
            DB::beginTransaction();
            $car->update($input);
            DB::commit();
            return redirect(route('cars.index'))->with('success', 'Vehículo guardado con éxito');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return redirect(route('cars.index'))->with('danger', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::withTrashed()->find($id);
        if (empty($car)) {
            return response()->json(['data' => [], 'success' => false, 'message' => 'Vehículo no encontrado']);
        }

        $message = "";
        if (!$car->deleted_at) {
            $car->delete();
            $message = "Vehículo desactivado con éxito";
        } else {
            $car->restore();
            $message = "Vehículo activado con éxito";
        }

        return response()->json(['data' => $car, 'success' => true, 'message' => $message]);
    }
}
