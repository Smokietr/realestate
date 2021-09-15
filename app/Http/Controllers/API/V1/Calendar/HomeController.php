<?php

namespace App\Http\Controllers\API\V1\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Repositories\LocationRepository;
use App\Http\Requests\API\V1\Calendar\IndexRequest;
use App\Http\Requests\API\V1\Calendar\StoreRequest;
use App\Http\Requests\API\V1\Calendar\UpdateRequest;
use App\Http\Resources\API\V1\Calendar\IndexResource;
use App\Models\Calendar;

class HomeController extends Controller
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        $query = Calendar::withOutGlobalScopes();

        $request->whenHas('status', function ($type) use ($query) {
            if($type != 'all') {
                return $query->where('status', '=', $type);
            }
        });

        $request->whenHas('started', function ($started) use ($query) {
            return $query->where('arrive_time', '>=', $started);
        });

        $request->whenHas('finished', function ($finished) use ($query) {
            return $query->where('arrive_time', '<=', $finished);
        });

        return $this->success(IndexResource::collection($query->paginate(10)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(StoreRequest $request)
    {

        return $this->locationRepository->distance('cm27pj', $request->get('code'))
                ->calculate($request->get('mode'))
                ->create($request->only(
                    ['customer', 'address', 'code', 'mode']
                ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Calendar $calendar)
    {
        return $this->success(new IndexResource($calendar));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Calendar $calendar)
    {
        $repository = $this->locationRepository;

        $request->whenHas('code', function ($code) use ($repository, $request) {
            return $repository->distance('cm27pj', $code)->calculate($request->get('mode'));
        });

        return $repository->update($calendar, $request->only(
            ['customer', 'address', 'status']
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        return $this->locationRepository->delete($calendar);
    }
}
