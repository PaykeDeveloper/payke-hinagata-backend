<?php

namespace Database\Factories\Sample;

use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportType;
use App\Models\Sample\CsvImportStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CsvImportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CsvImport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $created_at = $this->faker->dateTimeBetween('-30 days');
        return [
            'user_id' => User::factory(),
            'csv_type' => $this->faker->randomElement(CsvImportType::all()),
            'import_status' => $this->faker->randomElement(CsvImportStatus::all()),
            'file_name_original' => 'test_csv_' . $this->faker->randomDigit . '.csv',
            'file_name_system' => Str::random(60) . '.csv',
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
