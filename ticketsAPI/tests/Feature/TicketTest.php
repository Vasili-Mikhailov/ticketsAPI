<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllTickets()
    {
        $response = $this->get('/api/tickets');

        $response->assertStatus(200);
    }
    public function testStore()
    {
        $formData = [
            'title' => 'Title',
            'text' => 'This is text',
            'author_name' => 'Anton',
            'author_tel' => '79832106494'
        ];

        $response = $this->post('/api/store', $formData);

        $response->assertStatus(200);
    }
    public function testStoreWithEmptyData()
    {
        $formData = [];

        $response = $this->post('/api/store', $formData);

        $response->assertStatus(400);
    }
}
