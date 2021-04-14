<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectIndexRequest;
use App\Http\Requests\Sample\Project\ProjectShowRequest;
use App\Http\Requests\Sample\Project\ProjectUpdateRequest;
use App\Models\Sample\Division;
use App\Models\Sample\Project;
use Illuminate\Http\Response;
use Exception;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Division (親リソース) の Policy 適用
        $this->middleware('can:view,division');

        // 自身の Policy 適用
        $this->authorizeResource(Project::class, 'project', [
            // viewAny は認可に必要な追加モデルを手動で渡すので abilityMap から除外
            'except' => [ 'index', 'create', 'store' ],
        ]);
    }

    /**
     * @response [
     *   {
     *     "id": 7,
     *     "division_id": 18,
     *     "name": "new project",
     *     "created_at": "2021-04-13T07:30:58.000000Z",
     *     "updated_at": "2021-04-13T07:30:58.000000Z"
     *   }
     * ]
     *
     * @param ProjectIndexRequest $request
     * @return Response
     */
    public function index(ProjectIndexRequest $request, Division $division): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Project::class, $division]);

        return response($division->projects);
    }

    /**
     * @response {
     *   "name": "new project",
     *   "division_id": 18,
     *   "updated_at": "2021-04-13T07:30:58.000000Z",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "id": 7
     * }
     *
     * @param ProjectCreateRequest $request
     * @return Response
     */
    public function store(ProjectCreateRequest $request, Division $division): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Project::class, $division]);

        $project = Project::createWithDivision($division, $request->all());
        return response($project);
    }

    /**
     * @response {
     *   "id": 7,
     *   "division_id": 18,
     *   "name": "new project",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "updated_at": "2021-04-13T07:30:58.000000Z",
     *   "division": {
     *     "id": 18,
     *     "name": "テストカンパニー",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T04:12:36.000000Z"
     *   }
     * }
     *
     * @param ProjectShowRequest $request
     * @param Division $division
     * @param Project $project
     * @return Response
     */
    public function show(ProjectShowRequest $request, Division $division, Project $project): Response
    {
        return response($project);
    }

    /**
     * @response {
     *   "id": 7,
     *   "division_id": 18,
     *   "name": "new projectaaaa",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "updated_at": "2021-04-13T07:41:04.000000Z",
     *   "division": {
     *     "id": 18,
     *     "name": "テストカンパニー",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T04:12:36.000000Z"
     *   }
     * }
     *
     * @param ProjectUpdateRequest $request
     * @param Division $division
     * @param Project $project
     * @return Response
     */
    public function update(ProjectUpdateRequest $request, Division $division, Project $project): Response
    {
        $project->update($request->all());
        return response($project);
    }

    /**
     * @param ProjectShowRequest $request
     * @param Division $division
     * @param Project $project
     * @return Response
     * @throws Exception
     */
    public function destroy(ProjectShowRequest $request, Division $division, Project $project): Response
    {
        $project->delete();
        return response(null, 204);
    }
}
