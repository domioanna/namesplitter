<?php

namespace App\Http\Controllers;

use App\GetCsvContents;
use App\Namesplitter;
use Illuminate\Http\Request;

class NamesplitterController extends Controller
{
    /**
     * Get all rows from the CSV file, split them and return Json.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        // get CSV contents
        $csvContents = new GetCsvContents;
        $rows = $csvContents('examples__284_29.csv', true);

        // split
        $people = collect();

        foreach ($rows as $row) {
            $parse = new Namesplitter($row);
            $people->push($parse->split());
        }

        // return json
        return $people->flatten(1);
    }
}
