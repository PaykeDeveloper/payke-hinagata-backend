<?php

// FIXME: SAMPLE CODE

/** @noinspection PhpUnusedParameterInspection */

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
     * {
     * "id": 1,
     * "user_id": 4,
     * "division_id": 1,
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ],
     * "role_names": [
     * "Member"
     * ]
     * }
     * ]
     */
    public function index(Request $request, Division $division): Response
    {
        $members = Member::findFromRequest($request->user(), $division);
        return response($members);
    }

    /**
     * @response {
     * "id": 1,
     * "user_id": 4,
     * "division_id": 1,
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ],
     * "role_names": [
     * "Member"
     * ]
     * }
     */
    public function store(MemberCreateRequest $request, Division $division): Response
    {
        $member = Member::createFromRequest($request->validated(), $division);
        return response($member);
    }

    /**
     * @response {
     * "id": 1,
     * "user_id": 4,
     * "division_id": 1,
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ],
     * "role_names": [
     * "Member"
     * ]
     * }
     */
    public function show(Request $request, Division $division, Member $member): Response
    {
        return response($member);
    }

    /**
     * @response {
     * "id": 1,
     * "user_id": 4,
     * "division_id": 1,
     * "created_at": "2021-04-19T10:02:33.000000Z",
     * "updated_at": "2021-04-19T10:02:33.000000Z",
     * "permission_names": [
     * "project_viewAll",
     * "project_createAll",
     * "project_updateAll",
     * "project_deleteAll"
     * ],
     * "role_names": [
     * "Member"
     * ]
     * }
     */
    public function update(MemberUpdateRequest $request, Division $division, Member $member): Response
    {
        $updated_member = $member->updateFromRequest($request->validated());
        return response($updated_member);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, Division $division, Member $member): Response
    {
        $member->deleteFromRequest();
        return response(null, 204);
    }
}
