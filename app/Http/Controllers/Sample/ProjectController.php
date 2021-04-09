<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Sample\Project\ProjectIndexRequest;
use App\Http\Requests\Sample\Project\ProjectShowRequest;
use App\Models\Sample\Company;
use App\Models\Sample\Project;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    // /**
    //  * Get the map of resource methods to ability names.
    //  *
    //  * @return array
    //  */
    // protected function resourceAbilityMap()
    // {
    //     return [
    //         'show' => 'view',
    //         'create' => 'create',
    //         'store' => 'create',
    //         'edit' => 'update',
    //         'update' => 'update',
    //         'destroy' => 'delete',
    //         'viewAny' => 'viewAny',
    //     ];
    // }

    // protected function resourceMethodsWithoutModels()
    // {
    //     return ['viewAny', 'create', 'store'];
    // }

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
        return response($company->projects);
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
    //  * @param BookCreateRequest $request
    //  * @return Response
    //  */
    // public function store(BookCreateRequest $request): Response
    // {
    //     $book = Book::createWithUser($request->all(), $request->user());
    //     return response($book);
    // }

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
     * @param Project $project
     * @return Response
     */
    public function show(ProjectShowRequest $request, Project $project): Response
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

    // /**
    //  * @param BookShowRequest $request
    //  * @param Book $book
    //  * @return Response
    //  * @throws Exception
    //  */
    // public function destroy(BookShowRequest $request, Book $book): Response
    // {
    //     $book->delete();
    //     return response(null, 204);
    // }
}
