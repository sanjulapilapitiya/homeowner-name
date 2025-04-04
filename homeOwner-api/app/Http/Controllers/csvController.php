<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Person;

class csvController extends Controller
{
    public function uploadCSV(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048', // CSV file max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');

        // Open the file and read contents
        $csvData = [];
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // due to trailing commas, the last element of the array is empty this will trim it
                $row = array_filter($row, function ($value) {
                    return !empty($value);
                });

                $person = explode(' ',$row[0]);
                $andCheckedPersons = $this->andCheck($person);

                foreach ($andCheckedPersons as $andCheckedPerson) {
                    $homeOwnersName = $this->mapToPersonObject($andCheckedPerson);
                    $csvData[] = $homeOwnersName;
                }
            }
            fclose($handle);
        }

        return $csvData;
    }

    private function convertToPersonObject(string $title, ?string $first_name, ?string $initial, string $last_name)
    {
        return new Person($title, $first_name, $initial, $last_name);
    }

    private function mapToPersonObject(array $person)
    {
        if (count($person) === 3 && strlen(str_replace('.', '', $person[1])) > 1) {
            return $this->convertToPersonObject($person[0], $person[1], null, $person[2]);
        } elseif (count($person) === 3 && strlen(str_replace('.', '', $person[1])) === 1) {
            return $this->convertToPersonObject($person[0], null, str_replace('.', '', $person[1]), $person[2]);
        } elseif(count($person) === 2) {
            return $this->convertToPersonObject($person[0], null, null, $person[1]);
        } else {
            return $person;
        }
    }

    private function andCheck(array $person) {
        $andIndex = array_search('and', $person);
        $ampIndex = array_search('&', $person);
        if ($andIndex || $ampIndex) {
            if ($andIndex === 1) {
                $firstPerson = $person;
                unset($firstPerson[1]);
                unset($firstPerson[2]);
                $secondPerson = $person;
                unset($secondPerson[0]);
                unset($secondPerson[1]);
                return [array_values($firstPerson), array_values($secondPerson)];
            }
            if ($ampIndex === 1) {
                $firstPerson = $person;
                unset($firstPerson[1]);
                unset($firstPerson[2]);
                $secondPerson = $person;
                unset($secondPerson[0]);
                unset($secondPerson[1]);
                return [array_values($firstPerson), array_values($secondPerson)];
            }
            $firstPerson = array_slice($person, 0, $andIndex);
            $secondPerson = array_slice($person, $andIndex + 1);
            return [$firstPerson, $secondPerson];
        } elseif ($ampIndex) {
            $firstPerson = array_slice($person, 0, $ampIndex);
            $secondPerson = array_slice($person, $ampIndex + 1);
            return [$firstPerson, $secondPerson];
        } else {
            return [$person];
        }
    }
}