<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CsvControllerTest extends TestCase
{
    #[Test]
    public function it_processes_a_valid_csv_file_correctly()
    {
        // Create a temporary CSV with valid content
        $csvContent = <<<CSV
homeowner,
Mr John Smith,
Mr J. Smith,
Mr and Mrs Doe,
Dr & Mrs Bloggs,
Mr and Mrs Joe Doe,
Dr & Mrs M. Bloggs
CSV;
        $expectedResponse = [
            [
              "homeowner"
            ],
            [
              "title" => "Mr",
              "first_name" => "John",
              "initial" => null,
              "last_name" => "Smith"
            ],
            [
              "title" => "Mr",
              "first_name" => null,
              "initial" => "J",
              "last_name" => "Smith"
            ],
            [
              "title" => "Mr",
              "first_name" => null,
              "initial" => null,
              "last_name" => "Doe"
            ],
            [
              "title" => "Mrs",
              "first_name" => null,
              "initial" => null,
              "last_name" => "Doe"
            ],
            [
              "title" => "Dr",
              "first_name" => null,
              "initial" => null,
              "last_name" => "Bloggs"
            ],
            [
              "title" => "Mrs",
              "first_name" => null,
              "initial" => null,
              "last_name" => "Bloggs"
            ],
            [
              "title" => "Mr",
              "first_name" => "Joe",
              "initial" => null,
              "last_name" => "Doe"
            ],
            [
              "title" => "Mrs",
              "first_name" => "Joe",
              "initial" => null,
              "last_name" => "Doe"
            ],
            [
              "title" => "Dr",
              "first_name" => null,
              "initial" => "M",
              "last_name" => "Bloggs"
            ],
            [
              "title" => "Mrs",
              "first_name" => null,
              "initial" => "M",
              "last_name" => "Bloggs"
            ]
          ];

        $filePath = sys_get_temp_dir() . '/test.csv';
        file_put_contents($filePath, $csvContent);
        $file = new UploadedFile($filePath, 'test.csv', 'text/csv', null, true);

        $response = $this->postJson('/api/csv/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(200);

        $this->assertEquals($expectedResponse, $response->json());
    }

    #[Test]
    public function it_returns_an_error_for_invalid_file_upload()
    {
        $response = $this->postJson('/api/csv/upload', [
            // No file
        ]);
        $response->assertStatus(400);
    }
}