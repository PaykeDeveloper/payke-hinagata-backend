<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\Member\MemberCreateRequest;
use App\Http\Requests\Division\Member\MemberUpdateRequest;
use App\Models\Division\Division;
use App\Models\Division\Member;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view,division');
        $this->authorizeResource(Member::class);
    }

    /**
     * @response [
     *   {
     *     "id": 5,
     *     "user_id": 2,
     *     "division_id": 18,
     *     "created_at": "2021-04-13T04:52:40.000000Z",
     *     "updated_at": "2021-04-13T04:52:40.000000Z",
     *     "permissions": [],
     *     "roles": [
     *       {
     *         "id": 9,
     *         "name": "Division Manager",
     *         "guard_name": "web",
     *         "created_at": "2021-04-13T02:55:12.000000Z",
     *         "updated_at": "2021-04-13T02:55:12.000000Z",
     *         "pivot": {
     *           "model_id": 5,
     *           "role_id": 9,
     *           "model_type": "App\\Models\\Sample\\Member"
     *         }
     *       }
     *     ]
     *   }
     * ]
     *
     * @param Request $request
     * @param Division $division
     * @return Response
     */
    public function index(Request $request, Division $division): Response
    {
        return response($division->members);
    }

    /**
     * @response {
     *   "user_id": 3,
     *   "division_id": 18,
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "id": 7,
     *   "roles": [
     *     {
     *       "id": 9,
     *       "name": "Division Manager",
     *       "guard_name": "web",
     *       "created_at": "2021-04-13T02:55:12.000000Z",
     *       "updated_at": "2021-04-13T02:55:12.000000Z",
     *       "pivot": {
     *         "model_id": 7,
     *         "role_id": 9,
     *         "model_type": "App\\Models\\Sample\\Member"
     *       }
     *     }
     *   ]
     * }
     *
     * @param MemberCreateRequest $request
     * @return Response
     */
    public function store(MemberCreateRequest $request, Division $division): Response
    {
        $member = Member::createFromRequest($request->validated(), $division);
        return response($member);
    }

    /**
     * @response {
     *   "id": 7,
     *   "user_id": 3,
     *   "division_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z"
     * }
     *
     * @param Request $request
     * @param Division $division
     * @param Member $member
     * @return Response
     */
    public function show(Request $request, Division $division, Member $member): Response
    {
        return response($member);
    }

    /**
     * @response {
     *   "id": 7,
     *   "user_id": 3,
     *   "division_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "roles": [],
     *   "permissions": []
     * }
     *
     * @param MemberUpdateRequest $request
     * @param Division $division
     * @return Response
     */
    public function update(MemberUpdateRequest $request, Division $division, Member $member): Response
    {
        $member->updateFromRequest($request->validated());
        return response($member);
    }

    /**
     * @param Request $request
     * @param Division $division
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, Division $division, Member $member): Response
    {
        $member->deleteFromRequest();
        return response(null, 204);
    }
}
