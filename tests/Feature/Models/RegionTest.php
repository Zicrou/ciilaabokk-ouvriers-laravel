<?php

use App\Models\Region;
use Illuminate\Database\Eloquent\Model;

it('assigns a UUID when model events are disabled', function () {
    $region = Model::withoutEvents(fn () => Region::factory()->create([
        'nom' => 'Region 1',
    ]));

    expect($region->id)->toBeUuid();

    $this->assertModelExists($region);
});
