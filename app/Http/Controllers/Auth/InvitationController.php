<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Invitation\InvitationCreateRequest;
use App\Http\Requests\Auth\Invitation\InvitationDestroyRequest;
use App\Http\Requests\Auth\Invitation\InvitationUpdateRequest;
use App\Models\Auth\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    /**
     * @response [{
     * "id":9,
     * "user_id":1,
     * "email":"aaaaa@example.com",
     * "status":"pending",
     * "created_at":"2021-04-14T03:19:50.000000Z",
     * "updated_at":"2021-04-14T03:19:50.000000Z"
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
     * "id":9,
     * "user_id":1,
     * "email":"aaaaa@example.com",
     * "status":"pending",
     * "created_at":"2021-04-14T03:19:50.000000Z",
     * "updated_at":"2021-04-14T03:19:50.000000Z"
     * }
     *
     * @param InvitationCreateRequest $request
     * @return Response
     */
    public function store(InvitationCreateRequest $request): Response
    {
        $invitation = Invitation::createFromRequest($request->all(), $request->user());
        return response($invitation);
    }

    /**
     * @response {
     * "id":9,
     * "user_id":1,
     * "email":"aaaaa@example.com",
     * "status":"pending",
     * "created_at":"2021-04-14T03:19:50.000000Z",
     * "updated_at":"2021-04-14T03:19:50.000000Z"
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
     * "id":9,
     * "user_id":1,
     * "email":"aaaaa@example.com",
     * "status":"pending",
     * "created_at":"2021-04-14T03:19:50.000000Z",
     * "updated_at":"2021-04-14T03:19:50.000000Z"
     * }
     *
     * @param InvitationUpdateRequest $request
     * @param Invitation $invitation
     * @return Response
     */
    public function update(InvitationUpdateRequest $request, Invitation $invitation): Response
    {
        $invitation->update($request->all());
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
