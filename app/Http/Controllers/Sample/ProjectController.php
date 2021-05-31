<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Exports\CollectionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectUpdateRequest;
use App\Jobs\Sample\CreateProject;
use App\Jobs\Sample\UpdateProject;
use App\Models\Division\Division;
use App\Models\Sample\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view,division');
        $this->authorizeResource(Project::class);
    }

    /**
     * @response [{
     * "id":3,
     * "division_id":1,
     * "slug":"54b91d06-0cd1-478c-a61b-4ee6d23ae51f",
     * "name":"Test Project",
     * "description":"Foo\nBar",
     * "priority":"high",
     * "approved":true,
     * "start_date":"2021-05-01",
     * "finished_at":"2021-05-25T17:24:00.000000Z",
     * "difficulty":1,
     * "coefficient":2.2,
     * "productivity":33.3,
     * "lock_version":2,
     * "created_at":"2021-05-25T07:52:12.000000Z",
     * "updated_at":"2021-05-25T08:24:42.000000Z",
     * "deleted_at":null,
     * "cover_url":"http:\/\/localhost:8000\/storage\/1\/example.png"
     * }]
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
     * "id":3,
     * "division_id":1,
     * "slug":"54b91d06-0cd1-478c-a61b-4ee6d23ae51f",
     * "name":"Test Project",
     * "description":"Foo\nBar",
     * "priority":"high",
     * "approved":true,
     * "start_date":"2021-05-01",
     * "finished_at":"2021-05-25T17:24:00.000000Z",
     * "difficulty":1,
     * "coefficient":2.2,
     * "productivity":33.3,
     * "lock_version":2,
     * "created_at":"2021-05-25T07:52:12.000000Z",
     * "updated_at":"2021-05-25T08:24:42.000000Z",
     * "deleted_at":null,
     * "cover_url":"http:\/\/localhost:8000\/storage\/1\/example.png"
     * }
     *
     * @param ProjectCreateRequest $request
     * @param Division $division
     * @return Response
     */
    public function store(ProjectCreateRequest $request, Division $division): Response
    {
        $project = Project::createFromRequest($request->validated(), $division);
        return response($project);
    }

    /**
     * @response {
     * "id":3,
     * "division_id":1,
     * "slug":"54b91d06-0cd1-478c-a61b-4ee6d23ae51f",
     * "name":"Test Project",
     * "description":"Foo\nBar",
     * "priority":"high",
     * "approved":true,
     * "start_date":"2021-05-01",
     * "finished_at":"2021-05-25T17:24:00.000000Z",
     * "difficulty":1,
     * "coefficient":2.2,
     * "productivity":33.3,
     * "lock_version":2,
     * "created_at":"2021-05-25T07:52:12.000000Z",
     * "updated_at":"2021-05-25T08:24:42.000000Z",
     * "deleted_at":null,
     * "cover_url":"http:\/\/localhost:8000\/storage\/1\/example.png"
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
     * "id":3,
     * "division_id":1,
     * "slug":"54b91d06-0cd1-478c-a61b-4ee6d23ae51f",
     * "name":"Test Project",
     * "description":"Foo\nBar",
     * "priority":"high",
     * "approved":true,
     * "start_date":"2021-05-01",
     * "finished_at":"2021-05-25T17:24:00.000000Z",
     * "difficulty":1,
     * "coefficient":2.2,
     * "productivity":33.3,
     * "lock_version":2,
     * "created_at":"2021-05-25T07:52:12.000000Z",
     * "updated_at":"2021-05-25T08:24:42.000000Z",
     * "deleted_at":null,
     * "cover_url":"http:\/\/localhost:8000\/storage\/1\/example.png"
     * }
     *
     * @param ProjectUpdateRequest $request
     * @param Division $division
     * @param Project $project
     * @return Response
     */
    public function update(ProjectUpdateRequest $request, Division $division, Project $project): Response
    {
        $project->updateFromRequest($request->validated());
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

    /**
     * @param Request $request
     * @param Division $division
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function download(Request $request, Division $division): BinaryFileResponse
    {
        return Excel::download(new CollectionExport($division->projects), 'projects.csv');
    }

    /**
     * @param ProjectCreateRequest $request
     * @param Division $division
     * @return Response
     */
    public function storeAsync(ProjectCreateRequest $request, Division $division): Response
    {
        CreateProject::dispatch($division, $request->validated(), $request->user());
        return response(null, 204);
    }

    /**
     * @param ProjectUpdateRequest $request
     * @param Division $division
     * @param Project $project
     * @return Response
     */
    public function updateAsync(ProjectUpdateRequest $request, Division $division, Project $project): Response
    {
        UpdateProject::dispatch($project, $request->validated())->afterResponse();
        return response(null, 204);
    }
}
