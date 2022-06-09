<?php

namespace App;

class GetCsvContents
{
    public function __invoke($filename, $header = false)
    {
        // Check the resource is valid
        if (($handle = fopen(public_path('csv/' . $filename), 'r')) !== FALSE) {

            // Check opening the file is OK!
            while (($data = fgetcsv($handle)) !== FALSE) {
                foreach ($data as $row) {
                    if ($row !== "") $names[] = $row;
                }
            }
            fclose($handle);
        }

        if ($header) {
            array_shift($names);
        }

        return $names;
    }
}
