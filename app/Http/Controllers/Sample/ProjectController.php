<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectIndexRequest;
use App\Http\Requests\Sample\Project\ProjectShowRequest;
use App\Http\Requests\Sample\Project\ProjectUpdateRequest;
use App\Models\Sample\Company;
use App\Models\Sample\Project;
use Illuminate\Http\Response;
use Exception;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Company (親リソース) の Policy 適用
        $this->middleware('can:view,company');

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
     *     "company_id": 18,
     *     "name": "new project",
     *     "created_at": "2021-04-13T07:30:58.000000Z",
     *     "updated_at": "2021-04-13T07:30:58.000000Z"
     *   }
     * ]
     *
     * @param ProjectIndexRequest $request
     * @return Response
     */
    public function index(ProjectIndexRequest $request, Company $company): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Project::class, $company]);

        return response($company->projects);
    }

    /**
     * @response {
     *   "name": "new project",
     *   "company_id": 18,
     *   "updated_at": "2021-04-13T07:30:58.000000Z",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "id": 7
     * }
     *
     * @param ProjectCreateRequest $request
     * @return Response
     */
    public function store(ProjectCreateRequest $request, Company $company): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Project::class, $company]);

        $project = Project::createWithCompany($company, $request->all());
        return response($project);
    }

    /**
     * @response {
     *   "id": 7,
     *   "company_id": 18,
     *   "name": "new project",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "updated_at": "2021-04-13T07:30:58.000000Z",
     *   "company": {
     *     "id": 18,
     *     "name": "テストカンパニー",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T04:12:36.000000Z"
     *   }
     * }
     *
     * @param ProjectShowRequest $request
     * @param Company $company
     * @param Project $project
     * @return Response
     */
    public function show(ProjectShowRequest $request, Company $company, Project $project): Response
    {
        return response($project);
    }

    /**
     * @response {
     *   "id": 7,
     *   "company_id": 18,
     *   "name": "new projectaaaa",
     *   "created_at": "2021-04-13T07:30:58.000000Z",
     *   "updated_at": "2021-04-13T07:41:04.000000Z",
     *   "company": {
     *     "id": 18,
     *     "name": "テストカンパニー",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T04:12:36.000000Z"
     *   }
     * }
     *
     * @param ProjectUpdateRequest $request
     * @param Company $company
     * @param Project $project
     * @return Response
     */
    public function update(ProjectUpdateRequest $request, Company $company, Project $project): Response
    {
        $project->update($request->all());
        return response($project);
    }

    /**
     * @param ProjectShowRequest $request
     * @param Company $company
     * @param Project $project
     * @return Response
     * @throws Exception
     */
    public function destroy(ProjectShowRequest $request, Company $company, Project $project): Response
    {
        $project->delete();
        return response(null, 204);
    }
}
