<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Invitation\InvitationCreateRequest;
use App\Http\Requests\Common\Invitation\InvitationDestroyRequest;
use App\Http\Requests\Common\Invitation\InvitationUpdateRequest;
use App\Http\Resources\Common\InvitationResource;
use App\Models\Common\Invitation;
use App\Repositories\Common\InvitationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Common Invitation
 */
class InvitationController extends Controller
{
    private InvitationRepository $repository;

    public function __construct(InvitationRepository $repository)
    {
        $this->repository = $repository;
        $this->authorizeResource(Invitation::class);
    }

    /**
     * @response
     * [
     * {
     * "id": 3,
     * "status": "denied",
     * "name": "Prof. Tamia Hagenes",
     * "email": "huels.tad@beer.biz",
     * "created_by": 1,
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     * ]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $resources = Invitation::all();
        return InvitationResource::collection($resources);
    }

    /**
     * @response
     * {
     * "id": 3,
     * "status": "denied",
     * "name": "Prof. Tamia Hagenes",
     * "email": "huels.tad@beer.biz",
     * "created_by": 1,
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function store(InvitationCreateRequest $request): InvitationResource
    {
        $resource = $this->repository->store($request->validated(), $request->user());
        return InvitationResource::make($resource);
    }

    /**
     * @response
     * {
     * "id": 3,
     * "status": "denied",
     * "name": "Prof. Tamia Hagenes",
     * "email": "huels.tad@beer.biz",
     * "created_by": 1,
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function show(Request $request, Invitation $invitation): InvitationResource
    {
        return InvitationResource::make($invitation);
    }

    /**
     * @response
     * {
     * "id": 3,
     * "status": "denied",
     * "name": "Prof. Tamia Hagenes",
     * "email": "huels.tad@beer.biz",
     * "created_by": 1,
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function update(InvitationUpdateRequest $request, Invitation $invitation): InvitationResource
    {
        $resource = $this->repository->update($request->validated(), $invitation);
        return InvitationResource::make($resource);
    }

    public function destroy(InvitationDestroyRequest $request, Invitation $invitation): Response
    {
        $invitation->delete();
        return response()->noContent();
    }
}
