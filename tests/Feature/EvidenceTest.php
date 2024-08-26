<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EvidenceUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_evidence()
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'gkm']);
        $response = Response::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('evidence.pdf', 100);

        $this->post("/responses/{$response->id}/upload-evidence", [
            'evidence' => $file,
        ]);

        Storage::disk('public')->assertExists("evidences/{$file->hashName()}");
        $this->assertDatabaseHas('evidences', [
            'response_id' => $response->id,
            'file_path' => "evidences/{$file->hashName()}",
        ]);
    }
}
