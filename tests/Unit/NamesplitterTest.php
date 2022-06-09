<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Namesplitter;

class NamesplitterTest extends TestCase
{
    /** @test */
    public function it_splits_a_simple_name()
    {
        $parse = new Namesplitter('Mr John Doe');
        $person = $parse->split();

        $this->assertTrue($person->count() == 1);

        $this->assertTrue($person->toArray() == [
            [
                'title' => "Mr",
                'first_name' => "John",
                'initial' => null,
                'last_name' => "Doe"
        ]]);
    }

    /** @test */
    public function it_can_tell_if_the_string_contains_multiple_people()
    {
        $parse = new Namesplitter('Mr and Mrs Doe');

        $this->assertTrue($parse->hasMultiplePeople());
    }

    /** @test */
    public function it_splits_multiple_people()
    {
        $parse = new Namesplitter('Mr and Mrs Robert Smith');
        $persons = $parse->split();

        $this->assertTrue($persons->count() == 2);

        $this->assertTrue($persons->toArray() == [
            [
            'title' => "Mr",
            'first_name' => "Robert",
            'initial' => null,
            'last_name' => "Smith"
        ], [
            'title' => "Mrs",
            'first_name' => null,
            'initial' => null,
            'last_name' => "Smith"
        ]]);
    }

    /** @test */
    public function it_passes_test_1()
    {
        $parse = new Namesplitter('Mr John Smith');
        $person = $parse->split();

        $this->assertTrue($person->count() == 1);

        $this->assertTrue($person->toArray() == [
            [
                'title' => "Mr",
                'first_name' => "John",
                'initial' => null,
                'last_name' => "Smith"
            ]]);
    }

    /** @test */
    public function it_passes_test_2()
    {
        $parse = new Namesplitter('Mr and Mrs Smith');
        $persons = $parse->split();

        $this->assertTrue($persons->count() == 2);

        $this->assertTrue($persons->toArray() == [
            [
                'title' => "Mr",
                'first_name' => null,
                'initial' => null,
                'last_name' => "Smith"
            ], [
                'title' => "Mrs",
                'first_name' => null,
                'initial' => null,
                'last_name' => "Smith"
            ]]);
    }

    /** @test */
    public function it_passes_test_3()
    {
        $parse = new Namesplitter('Mr Tom Staff and Mr John Doe');
        $persons = $parse->split();

        $this->assertTrue($persons->count() == 2);

        $this->assertTrue($persons->toArray() == [
            [
                'title' => "Mr",
                'first_name' => "Tom",
                'initial' => null,
                'last_name' => "Staff"
            ], [
                'title' => "Mr",
                'first_name' => "John",
                'initial' => null,
                'last_name' => "Doe"
            ]]);
    }

    /** @test */
    public function it_passes_test_4()
    {
        $parse = new Namesplitter('Dr and Mrs Joe Bloggs');
        $persons = $parse->split();

        $this->assertTrue($persons->count() == 2);

        $this->assertTrue($persons->toArray() == [
            [
                'title' => "Dr",
                'first_name' => "Joe",
                'initial' => null,
                'last_name' => "Bloggs"
            ], [
                'title' => "Mrs",
                'first_name' => null,
                'initial' => null,
                'last_name' => "Bloggs"
            ]]);
    }
}
