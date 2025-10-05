<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EmployeePhotoTest extends TestCase
{
    use DatabaseTransactions;
	protected function setUp(): void
    {
        parent::setUp();
		app(PermissionRegistrar::class)->forgetCachedPermissions();
		Permission::findOrCreate('employees.view', 'web');
	}

    /** @test */
    public function signed_url_is_required_and_enforces_permissions()
    {
        Storage::fake('private');
        $this->employee = Employee::query()->first();
       if (! $this->employee) {
           $this->markTestSkipped('No employees available in test DB; import dump first.');
       };

        $photoDisk = 'public'; // or 'public' if that’s your config
       $photoPath = 'employees/photos/test-employee.jpg';
       Storage::disk($photoDisk)->put($photoPath, 'fake-image-bytes'); // small placeholder
       // If your model uses a column like photo_path/avatar, set it:
       $this->employee->forceFill(['photo_path' => $photoPath])->save();

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
