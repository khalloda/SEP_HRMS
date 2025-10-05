<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class EmployeePhotoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function signed_url_is_required_and_enforces_permissions()
    {
        Storage::fake('private');

        Permission::create(['name' => 'employees.view', 'guard_name' => 'web']);

        $employee = Employee::factory()->create([
            'photo_path' => 'employee_photos/test.jpg',
            'photo_mime_type' => 'image/jpeg',
        ]);

        Storage::disk('private')->put($employee->photo_path, 'fake-image');

        $user = User::factory()->create();
        $this->actingAs($user);

        // Without permission the generated URL should fall back to default avatar.
        $photoUrl = $employee->photo_url;
        $this->assertStringContainsString('ui-avatars.com', $photoUrl);

        $user->givePermissionTo('employees.view');
        $signedUrl = $employee->fresh()->photo_url;

        $this->assertStringContainsString('/employees/' . $employee->id . '/photo', $signedUrl);

        // Signed URL should work for authorized user.
        $response = $this->get($signedUrl);
        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/jpeg');

        // Hash tampering should fail.
        $tamperedUrl = preg_replace('/hash=[^&]+/', 'hash=invalid', $signedUrl);
        $this->get($tamperedUrl)->assertForbidden();

        // Removing signature should fail.
        $plainUrl = route('employee.photo', $employee);
        $this->get($plainUrl)->assertForbidden();
    }
}
