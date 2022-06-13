<?php

// FIXME: SAMPLE CODE

/** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\Member\MemberCreateRequest;
use App\Http\Requests\Division\Member\MemberUpdateRequest;
use App\Http\Resources\Division\MemberResource;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Repositories\Division\MemberRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Division Member
 */
class MemberController extends Controller
{
    private MemberRepository $repository;

    public function __construct(MemberRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('can:view,division');
        $this->authorizeResource(Member::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "division_id": 2,
     * "user_id": 1,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Division Manager"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     * ]
     */
    public function index(Request $request, Division $division): AnonymousResourceCollection
    {
        $resources = $this->repository->index($request->user(), $division);
        return MemberResource::collection($resources);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 2,
     * "user_id": 1,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Division Manager"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function store(MemberCreateRequest $request, Division $division): MemberResource
    {
        $resource = $this->repository->store($request->validated(), $division);
        return MemberResource::make($resource);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 2,
     * "user_id": 1,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Division Manager"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function show(Request $request, Division $division, Member $member): MemberResource
    {
        return MemberResource::make($member);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 2,
     * "user_id": 1,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Division Manager"
     * ],
     * "created_at": "2022-06-13T03:55:24.000000Z"
     * }
     */
    public function update(MemberUpdateRequest $request, Division $division, Member $member): MemberResource
    {
        $resource = $this->repository->update($request->validated(), $member);
        return MemberResource::make($resource);
    }

    public function destroy(Request $request, Division $division, Member $member): Response
    {
        $member->delete();
        return response()->noContent();
    }
}
