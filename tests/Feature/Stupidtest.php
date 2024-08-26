<?php

it('Does stupid', function () {
    $response = $this->get('/stupid');

    $response->assertStatus(200);
});
