<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Invitation\InvitationDestroyRequest;
use App\Http\Requests\Auth\Invitation\InvitationShowRequest;
use App\Models\Auth\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $invitations = Invitation::whereUserId($request->user()->id)->get();
        return response($invitations);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $token = Str::random(60);
        $attributes = $request->all() + ['token' => $token];
        $invitation = Invitation::create($attributes);
        $invitation->sendInvitationNotification($token, $request->getPreferredLanguage());
        return response($invitation);
    }

    /**
     *
     * @param InvitationShowRequest $request
     * @param Invitation $invitation
     * @return Response
     */
    public function show(InvitationShowRequest $request, Invitation $invitation): Response
    {
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
