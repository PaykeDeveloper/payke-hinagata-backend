<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Division\DivisionIndexRequest;
use App\Http\Requests\Sample\Division\DivisionShowRequest;
use App\Http\Requests\Sample\Division\DivisionUpdateRequest;
use App\Http\Requests\Sample\Member\MemberCreateRequest;
use App\Models\Sample\Division;
use App\Models\Sample\Member;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class MemberController extends Controller
{
    public function __construct()
    {
        // Division (親リソース) の Policy 適用
        $this->middleware('can:view,division');

        // Policy は追加パラメータが必要なので authorizeResource ではなく手動で呼び出す
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
     * @param DivisionIndexRequest $request
     * @return Response
     */
    public function index(DivisionIndexRequest $request, Division $division): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Member::class, $division]);

        foreach ($division->members as $member) {
            // 取得を行うと自動的にレスポンスに挿入される
            $member->getRoleNames();
            $member->getDirectPermissions();
        }
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
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Member::class, $division]);

        $userId = $request->input('user_id');

        /**
         * @var User|null
         */
        $user = User::find($userId);

        // Member として追加
        $member = Member::createWithUserAndDivision($user, $division);

        // Role を追加
        $roles = $request->input('roles');
        if (!is_null($roles)) {
            $member->syncRoles($roles);
        }

        // 取得を行うと自動的にレスポンスに挿入される
        $member->getAllPermissions();

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
     * @param DivisionShowRequest $request
     * @param Division $division
     * @param Member $member
     * @return Response
     */
    public function show(DivisionShowRequest $request, Division $division, Member $member): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Member::class, $division, $member]);

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
     * @param DivisionUpdateRequest $request
     * @param Division $division
     * @return Response
     */
    public function update(DivisionUpdateRequest $request, Division $division, Member $member): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Member::class, $division, $member]);

        // Role の更新
        $roles = $request->input('roles');
        if (!is_null($roles)) {
            $member->syncRoles($roles);
        }

        // 取得を行うと自動的にレスポンスに挿入される
        $member->getAllPermissions();

        $member->update($request->all());

        return response($member);
    }

    /**
     * @param DivisionShowRequest $request
     * @param Division $division
     * @return Response
     * @throws Exception
     */
    public function destroy(DivisionShowRequest $request, Division $division, Member $member): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Member::class, $division, $member]);

        // ロールの割り当て解除
        $member->roles()->detach();

        $member->delete();
        return response(null, 204);
    }
}
