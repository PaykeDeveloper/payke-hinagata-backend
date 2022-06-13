<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\Division\DivisionCreateRequest;
use App\Http\Requests\Division\Division\DivisionUpdateRequest;
use App\Http\Resources\Division\DivisionResource;
use App\Models\Division\Division;
use App\Models\User;
use App\Repositories\Division\DivisionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Division Division
 */
class DivisionController extends Controller
{
    private DivisionRepository $repository;

    public function __construct(DivisionRepository $repository)
    {
        $this->repository = $repository;
        $this->authorizeResource(Division::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "Juliet Kuhn",
     * "request_member_id": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     * ]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $resources = $this->repository->index($request->user());
        return DivisionResource::collection($resources);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "name": "Juliet Kuhn",
     * "request_member_id": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function store(DivisionCreateRequest $request): DivisionResource
    {
        /** @var User $user */
        $user = $request->user();
        $resource = $this->repository->store($request->validated(), $user);
        $user->members->load(['permissions', 'roles']);
        return DivisionResource::make($resource);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "name": "Juliet Kuhn",
     * "request_member_id": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function show(Request $request, Division $division): DivisionResource
    {
        return DivisionResource::make($division);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "name": "Juliet Kuhn",
     * "request_member_id": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function update(DivisionUpdateRequest $request, Division $division): DivisionResource
    {
        $resource = $this->repository->update($request->validated(), $division);
        return DivisionResource::make($resource);
    }

    public function destroy(Request $request, Division $division): Response
    {
        $division->delete();
        return response()->noContent();
    }
}
