<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Division\DivisionCreateRequest;
use App\Http\Requests\Sample\Division\DivisionIndexRequest;
use App\Http\Requests\Sample\Division\DivisionShowRequest;
use App\Http\Requests\Sample\Division\DivisionUpdateRequest;
use App\Models\Sample\Division;
use Exception;
use Illuminate\Http\Response;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Division::class, 'division');
    }

    /**
     * @response [
     *   {
     *     "id": 18,
     *     "name": "companpdafawefd)",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T09:33:04.000000Z"
     *   }
     * ]
     *
     * @param DivisionIndexRequest $request
     * @return Response
     */
    public function index(DivisionIndexRequest $request): Response
    {
        return response(Division::listByPermissions($request->user()));
    }

    /**
     * @response {
     *   "name": "brand new division",
     *   "updated_at": "2021-04-13T04:04:49.000000Z",
     *   "created_at": "2021-04-13T04:04:49.000000Z",
     *   "id": 16
     * }
     *
     * @param DivisionCreateRequest $request
     * @return Response
     */
    public function store(DivisionCreateRequest $request): Response
    {
        $division = Division::create($request->all());
        return response($division);
    }

    /**
     * @response {
     *   "id": 18,
     *   "name": "テストカンパニー",
     *   "created_at": "2021-04-13T04:12:36.000000Z",
     *   "updated_at": "2021-04-13T04:12:36.000000Z",
     *   "members": [
     *     {
     *       "id": 5,
     *       "user_id": 2,
     *       "division_id": 18,
     *       "created_at": "2021-04-13T04:52:40.000000Z",
     *       "updated_at": "2021-04-13T04:52:40.000000Z",
     *       "permissions": [],
     *       "roles": [
     *         {
     *           "id": 9,
     *           "name": "Division Manager",
     *           "guard_name": "web",
     *           "created_at": "2021-04-13T02:55:12.000000Z",
     *           "updated_at": "2021-04-13T02:55:12.000000Z",
     *           "pivot": {
     *             "model_id": 5,
     *             "role_id": 9,
     *             "model_type": "App\\Models\\Sample\\Member"
     *           }
     *         }
     *       ]
     *     }
     *   ]
     * }
     *
     * @param DivisionShowRequest $request
     * @param Division $division
     * @return Response
     */
    public function show(DivisionShowRequest $request, Division $division): Response
    {
        return response($division);
    }

    /**
     * @response {
     *   "id": 18,
     *   "name": "companpdafawefd)",
     *   "created_at": "2021-04-13T04:12:36.000000Z",
     *   "updated_at": "2021-04-13T09:33:04.000000Z",
     *   "members": [
     *     {
     *       "id": 5,
     *       "user_id": 2,
     *       "division_id": 18,
     *       "created_at": "2021-04-13T04:52:40.000000Z",
     *       "updated_at": "2021-04-13T04:52:40.000000Z",
     *       "permissions": [],
     *       "roles": [
     *         {
     *           "id": 9,
     *           "name": "Division Manager",
     *           "guard_name": "web",
     *           "created_at": "2021-04-13T02:55:12.000000Z",
     *           "updated_at": "2021-04-13T02:55:12.000000Z",
     *           "pivot": {
     *             "model_id": 5,
     *             "role_id": 9,
     *             "model_type": "App\\Models\\Sample\\Member"
     *           }
     *         }
     *       ]
     *     }
     *   ]
     * }
     *
     * @param DivisionUpdateRequest $request
     * @param Division $division
     * @return Response
     */
    public function update(DivisionUpdateRequest $request, Division $division): Response
    {
        $division->update($request->all());
        return response($division);
    }

    /**
     * @param DivisionShowRequest $request
     * @param Division $division
     * @return Response
     * @throws Exception
     */
    public function destroy(DivisionShowRequest $request, Division $division): Response
    {
        $division->delete();
        return response(null, 204);
    }
}
