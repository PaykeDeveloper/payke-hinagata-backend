<?php

// FIXME: SAMPLE CODE

/** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\Division\DivisionCreateRequest;
use App\Http\Requests\Division\Division\DivisionUpdateRequest;
use App\Models\Division\Division;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Division Division
 */
class DivisionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Division::class);
    }

    /**
     * @response [
     *   {
     *     "id": 18,
     *     "name": "companpdafawefd)",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T09:33:04.000000Z",
     *     "request_member_id": 1
     *   }
     * ]
     */
    public function index(Request $request): Response
    {
        return response(Division::getFromRequest($request->user()));
    }

    /**
     * @response {
     * "id": 1,
     * "name": "aaaaaaaaaaaa",
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "request_member_id": 1,
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ]
     * }
     */
    public function store(DivisionCreateRequest $request): Response
    {
        $division = Division::createFromRequest($request->validated(), $request->user());
        $division->setRequest($request->user());
        return response($division);
    }

    /**
     * @response {
     * "id": 1,
     * "name": "aaaaaaaaaaaa",
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "request_member_id": 1,
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ]
     * }
     */
    public function show(Request $request, Division $division): Response
    {
        $division->setRequest($request->user());
        return response($division);
    }

    /**
     * @response {
     * "id": 1,
     * "name": "aaaaaaaaaaaa",
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "request_member_id": 1,
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ]
     * }
     */
    public function update(DivisionUpdateRequest $request, Division $division): Response
    {
        $result = $division->updateFromRequest($request->validated());
        $result->setRequest($request->user());
        return response($result);
    }

    public function destroy(Request $request, Division $division): Response
    {
        $division->delete();
        return response(null, 204);
    }
}
