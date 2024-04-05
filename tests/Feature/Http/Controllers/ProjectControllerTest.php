<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProjectController
 */
final class ProjectControllerTest extends TestCase
{
    use AdditionalAssertions, WithFaker;

    #[Test]
    public function create_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProjectController::class,
            'create',
            \App\Http\Requests\ProjectCreateRequest::class
        );
    }

    #[Test]
    public function create_behaves_as_expected(): void
    {
        $response = $this->get(route('projects.create'));
    }
}
