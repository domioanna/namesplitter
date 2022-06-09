<?php

namespace App;

class Namesplitter
{
    /**
     * An array of words/characters that indicate multiple names.
     *
     * @var string[]
     */
    protected $joins = ['and', '&'];

    /**
     * Constructor: take input and sanitise it.
     *
     * @param $input
     */
    public function __construct($input)
    {
        $this->input = $input;

        $this->sanitise();
    }

    /**
     * Split up the input into Collection of people, singular or multiple.
     *
     * @return \Illuminate\Support\Collection
     */
    public function split()
    {
        $method = ($this->hasMultiplePeople()) ? "buildMultipleNames" : "buildSingleName";

        return collect($this->$method());
    }

    /**
     * Create a collection from a single name in the string
     *
     * @return array[]
     */
    public function buildSingleName(): array
    {
        $data = collect(explode(" ", $this->input));

        return [$this->buildPersonArray($data)];
    }

    /**
     * Create a collection from a single name in the string.
     *
     * @return array[]
     */
    public function buildMultipleNames(): array
    {
        // Separate into 2 data arrays, one for each person
        [$firstPersonData, $secondPersonData] = $this->splitIntoTwoPeople();

        if ($firstPersonData->count() == 1 and $secondPersonData->count() == 3) {
            $firstPersonData->push($secondPersonData->pull(1));
            $firstPersonData->push($secondPersonData[2]);
        }

        if ($firstPersonData->count() == 1) {
            $firstPersonData->push($secondPersonData->last());
        }

        $firstPerson = $this->buildPersonArray($firstPersonData->values());
        $secondPerson = $this->buildPersonArray($secondPersonData->values());

        return [$firstPerson, $secondPerson];
    }

    /**
     * Split the input into two items in an array, at the joining word.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function splitIntoTwoPeople()
    {
        return collect(explode($this->getJoiningWord(), $this->input))
            ->map(function($data){
                return collect(explode(" ", trim($data)));
            });
    }

    /**
     * Build a person array.
     *
     * @return array[]
     */
    protected function buildPersonArray($data): array
    {
        $person['title'] = $data->shift();
        $lastName = $data->pop();

        if (strlen($data->first()) > 1) {
            $person['first_name'] = $data->first();
            $person['initial'] = null;
        } else {
            $person['first_name'] = null;
            $person['initial'] = $data->first();
        }

        $person['last_name'] = $lastName;

        return $person;
    }

    /**
     * Sanitise the input, removing certain characters.
     */
    protected function sanitise()
    {
        $characters = [".", ","];

        $this->input = str_replace($characters, "", $this->input);
    }

    /**
     * Does the string contain multiple people, indicated by a joining word?
     *
     * @return bool
     */
    public function hasMultiplePeople(): bool
    {
        return (bool) $this->getJoiningWord();
    }

    /**
     * Get the joining word from the string.
     *
     * @return mixed
     */
    protected function getJoiningWord(): mixed
    {
        return collect($this->joins)->filter(function($join) {
            if (str_contains($this->input, $join)) return $join;
        })->first();
    }
}
