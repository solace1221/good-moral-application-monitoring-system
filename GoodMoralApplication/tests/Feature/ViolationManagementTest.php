<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use App\Models\Violation;
use App\Models\ViolationNotif;
use App\Models\StudentRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

class ViolationManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $student;
    protected $violation;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test admin user
        $this->admin = RoleAccount::create([
            'email' => 'admin@test.com',
            'fullname' => 'Test Admin',
            'student_id' => 'ADMIN-001',
            'account_type' => 'admin',
            'status' => '1',
            'password' => bcrypt('password123'),
            'department' => 'ADMIN'
        ]);

        // Create test student
        $this->student = RoleAccount::create([
            'email' => 'student@test.com',
            'fullname' => 'Test Student',
            'student_id' => 'STU-001',
            'account_type' => 'student',
            'status' => '1',
            'password' => bcrypt('password123'),
            'department' => 'SITE'
        ]);

        // Create student registration record
        StudentRegistration::create([
            'student_id' => 'STU-001',
            'fname' => 'Test',
            'lname' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password123'),
            'department' => 'SITE',
            'status' => '1'
        ]);

        // Create test violation type
        $this->violation = Violation::create([
            'offense_type' => 'minor',
            'description' => 'Late submission of requirements',
            'article' => 'Section 5, Article 1'
        ]);
    }

    /** @test */
    public function admin_can_add_single_violator()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.storeViolator'), [
            'first_name' => 'Test',
            'last_name' => 'Student',
            'student_id' => 'STU-001',
            'department' => 'SITE',
            'course' => 'BSIT',
            'offense_type' => 'minor',
            'violation' => 'Late submission of requirements',
            'ref_num' => 'REF-001'
        ]);

        // Check that violation was created
        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Late submission of requirements',
            'offense_type' => 'minor'
        ]);

        // Check that notification was created
        $this->assertDatabaseHas('violation_notifs', [
            'student_id' => 'STU-001',
            'ref_num' => 'REF-001'
        ]);

        // Check redirect and success message
        $response->assertRedirect('/admin/AddViolator');
        $response->assertSessionHas('success', 'Violator added successfully!');
    }

    /** @test */
    public function admin_can_add_multiple_students_single_violation()
    {
        $this->actingAs($this->admin);

        // Create additional test students
        $student2 = RoleAccount::create([
            'email' => 'student2@test.com',
            'fullname' => 'Test Student Two',
            'student_id' => 'STU-002',
            'account_type' => 'student',
            'status' => '1',
            'password' => bcrypt('password123'),
            'department' => 'SASTE'
        ]);

        $multipleStudentsData = json_encode([
            [
                'student_id' => 'STU-001',
                'first_name' => 'Test',
                'last_name' => 'Student',
                'department' => 'SITE',
                'course' => 'BSIT'
            ],
            [
                'student_id' => 'STU-002',
                'first_name' => 'Test',
                'last_name' => 'Student Two',
                'department' => 'SASTE',
                'course' => 'BS Psych'
            ]
        ]);

        $response = $this->post(route('admin.storeViolator'), [
            'offense_type' => 'minor',
            'violation' => 'Late submission of requirements',
            'ref_num' => 'REF-002',
            'multiple_students_data' => $multipleStudentsData
        ]);

        // Check that violations were created for both students
        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Late submission of requirements'
        ]);

        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-002',
            'violation' => 'Late submission of requirements'
        ]);

        // Check redirect and success message
        $response->assertRedirect('/admin/AddViolator');
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Successfully added violation for 2 students!', session('success'));
    }

    /** @test */
    public function admin_can_add_multiple_violations_single_student()
    {
        $this->actingAs($this->admin);

        // Create additional violation types
        $violation2 = Violation::create([
            'offense_type' => 'minor',
            'description' => 'Dress code violation',
            'article' => 'Section 5, Article 2'
        ]);

        $multipleViolationsData = json_encode([
            [
                'description' => 'Late submission of requirements',
                'article' => 'Section 5, Article 1'
            ],
            [
                'description' => 'Dress code violation',
                'article' => 'Section 5, Article 2'
            ]
        ]);

        $response = $this->post(route('admin.storeViolator'), [
            'first_name' => 'Test',
            'last_name' => 'Student',
            'student_id' => 'STU-001',
            'department' => 'SITE',
            'course' => 'BSIT',
            'offense_type' => 'minor',
            'ref_num' => 'REF-003',
            'multiple_violations_data' => $multipleViolationsData
        ]);

        // Check that both violations were created for the student
        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Late submission of requirements'
        ]);

        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Dress code violation'
        ]);

        // Check redirect and success message
        $response->assertRedirect('/admin/AddViolator');
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Successfully added 2 violations for the student!', session('success'));
    }

    /** @test */
    public function admin_can_add_multiple_violations_multiple_students()
    {
        $this->actingAs($this->admin);

        // Create additional test student
        $student2 = RoleAccount::create([
            'email' => 'student2@test.com',
            'fullname' => 'Test Student Two',
            'student_id' => 'STU-002',
            'account_type' => 'student',
            'status' => '1',
            'password' => bcrypt('password123'),
            'department' => 'SASTE'
        ]);

        // Create additional violation type
        $violation2 = Violation::create([
            'offense_type' => 'minor',
            'description' => 'Dress code violation',
            'article' => 'Section 5, Article 2'
        ]);

        $multipleStudentsData = json_encode([
            [
                'student_id' => 'STU-001',
                'first_name' => 'Test',
                'last_name' => 'Student',
                'department' => 'SITE',
                'course' => 'BSIT'
            ],
            [
                'student_id' => 'STU-002',
                'first_name' => 'Test',
                'last_name' => 'Student Two',
                'department' => 'SASTE',
                'course' => 'BS Psych'
            ]
        ]);

        $multipleViolationsData = json_encode([
            [
                'description' => 'Late submission of requirements',
                'article' => 'Section 5, Article 1'
            ],
            [
                'description' => 'Dress code violation',
                'article' => 'Section 5, Article 2'
            ]
        ]);

        $response = $this->post(route('admin.storeViolator'), [
            'offense_type' => 'minor',
            'ref_num' => 'REF-004',
            'multiple_students_data' => $multipleStudentsData,
            'multiple_violations_data' => $multipleViolationsData
        ]);

        // Check that all combinations were created (2 students × 2 violations = 4 records)
        $this->assertEquals(4, StudentViolation::count());

        // Check specific combinations
        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Late submission of requirements'
        ]);

        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'violation' => 'Dress code violation'
        ]);

        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-002',
            'violation' => 'Late submission of requirements'
        ]);

        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-002',
            'violation' => 'Dress code violation'
        ]);

        // Check redirect and success message
        $response->assertRedirect('/admin/AddViolator');
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Successfully added 2 violations for 2 students (4 total violation records created)!', session('success'));
    }

    /** @test */
    public function test_redirect_behavior_after_adding_violator()
    {
        $this->actingAs($this->admin);

        // Test that the form stays on the same page and doesn't redirect to welcome
        $response = $this->post(route('admin.storeViolator'), [
            'first_name' => 'Test',
            'last_name' => 'Student',
            'student_id' => 'STU-001',
            'department' => 'SITE',
            'course' => 'BSIT',
            'offense_type' => 'minor',
            'violation' => 'Late submission of requirements',
            'ref_num' => 'REF-005'
        ]);

        // Verify it redirects to AddViolator page, NOT welcome page
        $response->assertRedirect('/admin/AddViolator');

        // Verify it doesn't redirect to welcome page
        $this->assertNotEquals('/', $response->getTargetUrl());
        $this->assertNotEquals(url('/'), $response->getTargetUrl());

        // Verify success message is present
        $response->assertSessionHas('success');

        // Follow the redirect to ensure the page loads correctly
        $followResponse = $this->get('/admin/AddViolator');
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Add Violator');
        $followResponse->assertSee('Violator added successfully!');
    }

    /** @test */
    public function test_validation_errors_stay_on_same_page()
    {
        $this->actingAs($this->admin);

        // Test with missing required fields
        $response = $this->post(route('admin.storeViolator'), [
            'first_name' => '',
            'last_name' => '',
            'student_id' => '',
            'department' => '',
            'course' => '',
            'offense_type' => '',
            'violation' => '',
        ]);

        // Should redirect back with validation errors
        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'student_id',
            'department',
            'course',
            'offense_type',
            'violation'
        ]);
    }

    /** @test */
    public function test_escalation_functionality()
    {
        $this->actingAs($this->admin);

        // Add 2 minor violations first
        for ($i = 1; $i <= 2; $i++) {
            $this->post(route('admin.storeViolator'), [
                'first_name' => 'Test',
                'last_name' => 'Student',
                'student_id' => 'STU-001',
                'department' => 'SITE',
                'course' => 'BSIT',
                'offense_type' => 'minor',
                'violation' => "Minor violation {$i}",
                'ref_num' => "REF-00{$i}"
            ]);
        }

        // Add the 3rd minor violation which should trigger escalation
        $response = $this->post(route('admin.storeViolator'), [
            'first_name' => 'Test',
            'last_name' => 'Student',
            'student_id' => 'STU-001',
            'department' => 'SITE',
            'course' => 'BSIT',
            'offense_type' => 'minor',
            'violation' => 'Third minor violation',
            'ref_num' => 'REF-003'
        ]);

        // Check that escalation message is shown
        $response->assertSessionHas('success');
        $this->assertStringContainsString('AUTOMATIC ESCALATION', session('success'));
        $this->assertStringContainsString('MAJOR VIOLATION', session('success'));

        // Verify a major violation was automatically created
        $this->assertDatabaseHas('student_violations', [
            'student_id' => 'STU-001',
            'offense_type' => 'major'
        ]);
    }

    /** @test */
    public function test_form_submission_fix()
    {
        // This test verifies that the form submission fix works correctly
        $this->actingAs($this->admin);

        // Test that the form uses proper Laravel form submission
        $response = $this->get(route('admin.AddViolator'));

        // Check that the form has the correct action and method
        $response->assertSee('action="' . route('admin.storeViolator') . '"', false);
        $response->assertSee('method="POST"', false);

        // Check that CSRF token is present
        $response->assertSee('@csrf', false);

        // Check that the submit button is now type="submit" instead of type="button"
        $response->assertSee('type="submit"', false);
        $response->assertSee('validateAndPrepareForm()', false);

        // Verify the page loads correctly
        $response->assertStatus(200);
        $response->assertSee('Add Violator');
    }
}
