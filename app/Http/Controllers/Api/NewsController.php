<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateNewsRequest;
use App\Http\Requests\Api\UpdateNewsRequest;
use App\Models\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except([
            'index','show',
        ]);

        $this->middleware('auth:api')->only([
            'index','show',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return News::where(function ($query) use ($request) {
            if ($request->input('type')) {
                $query->where('type', '=', $request->input('type'));
            }
        })->paginate(20);
    }

    /**
     * @param CreateNewsRequest $request
     * @return mixed
     */
    public function store(CreateNewsRequest $request)
    {

        $record = News::create($request->input());

        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return News::findOrFail($id);
    }

    /**
     * @param UpdateNewsRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        $item = News::findOrFail($id);
        $item->update($request->input());

        return $item;
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $item = News::findOrFail($id);
        $item->delete();
    }
}
