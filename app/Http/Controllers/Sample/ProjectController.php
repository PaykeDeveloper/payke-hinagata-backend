<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectUpdateRequest;
use App\Models\Division\Division;
use App\Models\Sample\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view,division');
        $this->authorizeResource(Project::class);
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
     * @param Request $request
     * @param Division $division
     * @return Response
     */
    public function index(Request $request, Division $division): Response
    {
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
        $project = Project::createFromRequest($request->validated(), $division);
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
     * @param Request $request
     * @param Division $division
     * @param Project $project
     * @return Response
     */
    public function show(Request $request, Division $division, Project $project): Response
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
        $project->update($request->validated());
        return response($project);
    }

    /**
     * @param Request $request
     * @param Division $division
     * @param Project $project
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, Division $division, Project $project): Response
    {
        $project->delete();
        return response(null, 204);
    }
}
