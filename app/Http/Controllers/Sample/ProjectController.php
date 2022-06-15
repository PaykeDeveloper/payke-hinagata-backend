<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Exports\CollectionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Project\ProjectCreateRequest;
use App\Http\Requests\Sample\Project\ProjectUpdateRequest;
use App\Http\Resources\Sample\ProjectResource;
use App\Jobs\Sample\CreateProject;
use App\Jobs\Sample\UpdateProject;
use App\Models\Division\Division;
use App\Models\Sample\Project;
use App\Repositories\Sample\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @group Sample Project
 */
class ProjectController extends Controller
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('can:view,division');
        $this->authorizeResource(Project::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "division_id": 1,
     * "member_id": 83,
     * "slug": "723fd6ef-d0db-3fac-81a8-64866fb515a7",
     * "name": "Grant Gerlach DVM",
     * "cover_url": "http://localhost:8000/storage/1/blanditiis-est-nisi-animi-dolore-quasi-animi-recusandae.png",
     * "description": "Vel sint ea rerum qui. Eligendi possimus sint quae assumenda occaecati qui.",
     * "priority": "high",
     * "approved": true,
     * "start_date": "1976-11-21",
     * "finished_at": "2003-01-08T16:32:06.000000Z",
     * "difficulty": 5,
     * "coefficient": 57,
     * "productivity": 821513.232,
     * "lock_version": 9,
     * "created_at": "2022-06-13T03:55:24.000000Z",
     * "deleted_at": null
     * }
     * ]
     */
    public function index(Request $request, Division $division): AnonymousResourceCollection
    {
        return ProjectResource::collection($division->projects);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 1,
     * "member_id": 83,
     * "slug": "723fd6ef-d0db-3fac-81a8-64866fb515a7",
     * "name": "Grant Gerlach DVM",
     * "cover_url": "http://localhost:8000/storage/1/blanditiis-est-nisi-animi-dolore-quasi-animi-recusandae.png",
     * "description": "Vel sint ea rerum qui. Eligendi possimus sint quae assumenda occaecati qui.",
     * "priority": "high",
     * "approved": true,
     * "start_date": "1976-11-21",
     * "finished_at": "2003-01-08T16:32:06.000000Z",
     * "difficulty": 5,
     * "coefficient": 57,
     * "productivity": 821513.232,
     * "lock_version": 9,
     * "created_at": "2022-06-13T03:55:24.000000Z",
     * "deleted_at": null
     * }
     */
    public function store(ProjectCreateRequest $request, Division $division): ProjectResource
    {
        $resource = $this->repository->store($request->validated(), $division);
        return ProjectResource::make($resource);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 1,
     * "member_id": 83,
     * "slug": "723fd6ef-d0db-3fac-81a8-64866fb515a7",
     * "name": "Grant Gerlach DVM",
     * "cover_url": "http://localhost:8000/storage/1/blanditiis-est-nisi-animi-dolore-quasi-animi-recusandae.png",
     * "description": "Vel sint ea rerum qui. Eligendi possimus sint quae assumenda occaecati qui.",
     * "priority": "high",
     * "approved": true,
     * "start_date": "1976-11-21",
     * "finished_at": "2003-01-08T16:32:06.000000Z",
     * "difficulty": 5,
     * "coefficient": 57,
     * "productivity": 821513.232,
     * "lock_version": 9,
     * "created_at": "2022-06-13T03:55:24.000000Z",
     * "deleted_at": null
     * }
     */
    public function show(Request $request, Division $division, Project $project): ProjectResource
    {
        return ProjectResource::make($project);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "division_id": 1,
     * "member_id": 83,
     * "slug": "723fd6ef-d0db-3fac-81a8-64866fb515a7",
     * "name": "Grant Gerlach DVM",
     * "cover_url": "http://localhost:8000/storage/1/blanditiis-est-nisi-animi-dolore-quasi-animi-recusandae.png",
     * "description": "Vel sint ea rerum qui. Eligendi possimus sint quae assumenda occaecati qui.",
     * "priority": "high",
     * "approved": true,
     * "start_date": "1976-11-21",
     * "finished_at": "2003-01-08T16:32:06.000000Z",
     * "difficulty": 5,
     * "coefficient": 57,
     * "productivity": 821513.232,
     * "lock_version": 9,
     * "created_at": "2022-06-13T03:55:24.000000Z",
     * "deleted_at": null
     * }
     */
    public function update(ProjectUpdateRequest $request, Division $division, Project $project): ProjectResource
    {
        $resource = $this->repository->update($request->validated(), $project);
        return ProjectResource::make($resource);
    }

    public function destroy(Request $request, Division $division, Project $project): Response
    {
        $project->delete();
        return response()->noContent();
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function download(Request $request, Division $division): BinaryFileResponse
    {
        return Excel::download(new CollectionExport($division->projects), 'projects.csv');
    }

    public function storeAsync(ProjectCreateRequest $request, Division $division): Response
    {
        CreateProject::dispatch($division, $request->validated(), $request->user());
        return response()->noContent();
    }

    public function updateAsync(ProjectUpdateRequest $request, Division $division, Project $project): Response
    {
        UpdateProject::dispatch($project, $request->validated())->afterResponse();
        return response()->noContent();
    }
}
