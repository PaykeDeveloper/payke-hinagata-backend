<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectIndexRequest;
use App\Http\Requests\Sample\Project\ProjectShowRequest;
use App\Models\Sample\Company;
use App\Models\Sample\Project;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Company (親リソース) の Policy 適用
        $this->middleware('can:view,company');

        // 自身の Policy 適用
        $this->authorizeResource(Project::class, 'project', [
            // viewAny は認可に必要な追加モデルを手動で渡すので abilityMap から除外
            'except' => [ 'index' ],
        ]);
    }

    /**
     * @response [
     * {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     * ]
     *
     * @param ProjectIndexRequest $request
     * @return Response
     */
    public function index(ProjectIndexRequest $request, Company $company): Response
    {
        // viewAny へ手動で company を渡す
        $this->authorize('viewAny', [Project::class, $company]);

        return response($company->projects);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param ProjectCreateRequest $request
     * @return Response
     */
    public function store(ProjectCreateRequest $request, Company $company): Response
    {
        $project = Project::createWithCompany($company, $request->all());
        return response($project);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
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

    // /**
    //  * @response {
    //  * "id": 2,
    //  * "user_id": 1,
    //  * "title": "Title 1",
    //  * "author": "Author 1",
    //  * "release_date": "2021-03-16",
    //  * "created_at": "2021-03-05T08:31:33.000000Z",
    //  * "updated_at": "2021-03-05T08:31:33.000000Z"
    //  * }
    //  *
    //  * @param BookUpdateRequest $request
    //  * @param Book $book
    //  * @return Response
    //  */
    // public function update(BookUpdateRequest $request, Book $book): Response
    // {
    //     $book->update($request->all());
    //     return response($book);
    // }

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
