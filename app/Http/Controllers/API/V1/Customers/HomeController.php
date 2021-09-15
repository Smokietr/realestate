<?php

namespace App\Http\Controllers\API\V1\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Customers\IndexRequest;
use App\Http\Requests\API\V1\Customers\StoreRequest;
use App\Http\Requests\API\V1\Customers\UpdateRequest;
use App\Http\Resources\API\V1\Customers\IndexResource;
use App\Models\Customers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        return $this->success(IndexResource::collection(Customers::paginate(10)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $parameters = $request->only(['name', 'lastname', 'email', 'phone']);

        $create = Customers::create($parameters);

        if($create) {
            return $this->success(new IndexResource($create));
        }

        return $this->unknownError();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Customers $customer)
    {
        return $this->success(new IndexResource($customer));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Customers $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Customers $customer)
    {
        $parameters = $request->only(['name', 'lastname', 'email', 'phone']);

        if($customer->update($parameters)) {
            return $this->success(new IndexResource($customer));
        }

        return $this->unknownError();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function destroy(Customers $customer)
    {
        if($customer->delete()) {
            return $this->success(['id' => $customer->id]);
        }

        return $this->unknownError();
    }
}
