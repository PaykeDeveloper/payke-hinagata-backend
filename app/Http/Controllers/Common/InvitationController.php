<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Invitation\InvitationCreateRequest;
use App\Http\Requests\Common\Invitation\InvitationDestroyRequest;
use App\Http\Requests\Common\Invitation\InvitationUpdateRequest;
use App\Models\Common\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    /**
     * @response [{
     * "id":1,
     * "status":"pending",
     * "name":"Ova Ankunding",
     * "email":"jayden.goodwin@nicolas.org",
     * "created_by":1,
     * "updated_at":"2021-04-14T07:55:48.000000Z",
     * "created_at":"2021-04-14T07:55:48.000000Z",
     * }]
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $invitations = Invitation::all();
        return response($invitations);
    }

    /**
     * @response {
     * "id":1,
     * "status":"pending",
     * "name":"Ova Ankunding",
     * "email":"jayden.goodwin@nicolas.org",
     * "created_by":1,
     * "updated_at":"2021-04-14T07:55:48.000000Z",
     * "created_at":"2021-04-14T07:55:48.000000Z",
     * }
     *
     * @param InvitationCreateRequest $request
     * @return Response
     */
    public function store(InvitationCreateRequest $request): Response
    {
        $invitation = Invitation::createFromRequest($request->validated(), $request->user());
        return response($invitation);
    }

    /**
     * @response {
     * "id":1,
     * "status":"pending",
     * "name":"Ova Ankunding",
     * "email":"jayden.goodwin@nicolas.org",
     * "created_by":1,
     * "updated_at":"2021-04-14T07:55:48.000000Z",
     * "created_at":"2021-04-14T07:55:48.000000Z",
     * }
     *
     * @param Request $request
     * @param Invitation $invitation
     * @return Response
     */
    public function show(Request $request, Invitation $invitation): Response
    {
        return response($invitation);
    }

    /**
     * @response {
     * "id":1,
     * "status":"pending",
     * "name":"Ova Ankunding",
     * "email":"jayden.goodwin@nicolas.org",
     * "created_by":1,
     * "updated_at":"2021-04-14T07:55:48.000000Z",
     * "created_at":"2021-04-14T07:55:48.000000Z",
     * }
     *
     * @param InvitationUpdateRequest $request
     * @param Invitation $invitation
     * @return Response
     */
    public function update(InvitationUpdateRequest $request, Invitation $invitation): Response
    {
        $invitation->update($request->validated());
        return response($invitation);
    }

    /**
     *
     * @param InvitationDestroyRequest $request
     * @param Invitation $invitation
     * @return Response
     * @throws \Exception
     */
    public function destroy(InvitationDestroyRequest $request, Invitation $invitation): Response
    {
        $invitation->delete();
        return response(null, 204);
    }
}
