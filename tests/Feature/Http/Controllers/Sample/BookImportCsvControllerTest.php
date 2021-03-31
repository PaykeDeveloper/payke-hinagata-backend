<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\CsvImport;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookImportCsvControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $csvImport = CsvImport::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/csv-upload/books');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($csvImport->toArray());
    }

    /**
     * 作成ができる。
     */
    public function testStoreSuccess()
    {
        Excel::fake();
        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            implode("\n", [
                'title, author, release_date',
                '"book title","author name","2021-01-01"',
            ])
        );
        $response = $this->postJson('/api/v1/csv-upload/books', [
            'csv_file' => $file,
        ]);
        $response->assertOk()
            ->assertJsonPath('file_name_original', 'test.csv');
        Storage::disk('local')->assertExists('import-csvs/'.$file->hashName());
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccess()
    {
        $csvImport = CsvImport::factory()->create(['user_id' => $this->user->id]);
        $response = $this->getJson('/api/v1/csv-upload/books/' . $csvImport->id);
        $response->assertOk()
            ->assertJson($csvImport->toArray());
    }

    /**
     * [準正常系]
     */
    /**
     * 更新ができない。
     */
    public function testUpdateSuccess()
    {
        $csvImport = CsvImport::factory()->create(['user_id' => $this->user->id]);
        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            implode("\n", [
                'title, author, release_date',
                '"book title","author name","2021-01-01"',
            ])
        );
        $response = $this->postJson('/api/v1/csv-upload/books/' . $csvImport->id, [
            'csv_file' => $file,
        ]);
        $response->assertStatus(405);
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccess()
    {
        $csvImport = CsvImport::factory()->create(['user_id' => $this->user->id]);
        $response = $this->deleteJson('/api/v1/csv-upload/books/' . $csvImport->id);
        $response->assertStatus(405);
        $this->assertNotNull(CsvImport::find($csvImport->id));
    }

    /**
     * ユーザーに紐づかないデータは取得されない。
     */
    public function testIndexEmpty()
    {
        CsvImport::factory()->create();
        $response = $this->getJson('/api/v1/csv-upload/books');
        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * ユーザーに紐づかないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $csvImport = CsvImport::factory()->create();
        $response = $this->getJson('/api/v1/csv-upload/books/' . $csvImport->id);
        $response->assertNotFound();
    }

    /**
     * バリデーションエラー:ファイル未指定
     */
    public function testStoreRequiredCsvFile()
    {
        Excel::fake();
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->postJson('/api/v1/csv-upload/books', [
            'file' => $file,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['csv_file']]);
    }

    /**
     * バリデーションエラー:ファイルフォーマット異常
     */
    public function testStoreMimeTypeCsvFile()
    {
        Excel::fake();
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->postJson('/api/v1/csv-upload/books', [
            'csv_file' => $file,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['csv_file']]);
    }

    /**
     * バリデーションエラー:ファイルフォーマット異常
     */
    public function testStoreLimitSizeCsvFile()
    {
        Excel::fake();
        Storage::fake('local');
        $csv = [
            'title, author, release_date',
        ];
        for($i = 0; $i < 50000; $i++) {
            $csv[] = '"book title","author name","2021-01-01"';
        }
        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            implode("\n", $csv)
        );
        $response = $this->postJson('/api/v1/csv-upload/books', [
            'csv_file' => $file,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['csv_file']]);
    }
}
