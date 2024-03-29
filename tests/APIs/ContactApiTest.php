<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Contact;

class ContactApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contact()
    {
        $contact = factory(Contact::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contacts', $contact
        );

        $this->assertApiResponse($contact);
    }

    /**
     * @test
     */
    public function test_read_contact()
    {
        $contact = factory(Contact::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/contacts/'.$contact->id
        );

        $this->assertApiResponse($contact->toArray());
    }

    /**
     * @test
     */
    public function test_update_contact()
    {
        $contact = factory(Contact::class)->create();
        $editedContact = factory(Contact::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contacts/'.$contact->id,
            $editedContact
        );

        $this->assertApiResponse($editedContact);
    }

    /**
     * @test
     */
    public function test_delete_contact()
    {
        $contact = factory(Contact::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contacts/'.$contact->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contacts/'.$contact->id
        );

        $this->response->assertStatus(404);
    }
}
