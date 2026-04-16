<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportUsersRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\RoleAccount;
use App\Models\User;
use App\Models\Course;
use App\Services\AccountManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
  protected AccountManagementService $accountService;

  public function __construct(AccountManagementService $accountService)
  {
    $this->accountService = $accountService;
  }

  public function AddAccountnt(Request $request)
  {
    // Start with base query
    $query = RoleAccount::query();

    // Apply search filters if any field is provided
    if ($request->filled('search_name')) {
      $query->where('fullname', 'LIKE', '%' . $request->search_name . '%');
    }

    if ($request->filled('search_student_id')) {
      $query->where('student_id', 'LIKE', '%' . $request->search_student_id . '%');
    }

    if ($request->filled('search_email')) {
      $query->where('email', 'LIKE', '%' . $request->search_email . '%');
    }

    if ($request->filled('search_department')) {
      $query->where('department', $request->search_department);
    }

    if ($request->filled('search_account_type')) {
      $query->where('account_type', $request->search_account_type);
    }

    if ($request->filled('search_status')) {
      $query->where('status', $request->search_status);
    }

    // Order by fullname for consistent results
    $query->orderBy('fullname', 'asc');

    // Paginate results (preserve search parameters in pagination links)
    $students = $query->paginate(10)->appends($request->query());

    return view('admin.add-account', compact('students'));
  }

  /**
   * Get account data for editing
   */
  public function editAccount($id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      // Look up separate name parts from users table
      $user = User::where('email', $account->email)->first();
      $accountData = $account->toArray();
      $accountData['first_name'] = $user->firstname ?? '';
      $accountData['last_name'] = $user->lastname ?? '';
      $accountData['middle_initial'] = $account->mname ?? $user->middlename ?? '';
      $accountData['extension_name'] = $account->extension ?? $user->suffix_name ?? '';
      $accountData['is_imported'] = $account->created_via === 'import';

      return response()->json([
        'success' => true,
        'account' => $accountData
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Account not found'
      ], 404);
    }
  }

  /**
   * Update account information
   */
  public function updateAccount(UpdateAccountRequest $request, $id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      // Prevent account type change for student accounts
      if ($account->account_type === 'student' && $request->account_type !== 'student') {
        return redirect()->route('admin.AddAccount')
          ->with('error', 'Student accounts cannot be changed to a different account type.');
      }

      // Prevent account type change for imported accounts
      if ($account->created_via === 'import' && $request->account_type !== $account->account_type) {
        return redirect()->route('admin.AddAccount')
          ->with('error', 'Account type cannot be changed for students imported via the Import Students feature.');
      }

      $validated = $request->validated();

      // Build fullname from separate name fields: First MI. Last Ext
      $firstName = trim($validated['first_name']);
      $lastName = trim($validated['last_name']);
      $middleInitial = $validated['middle_initial'] ?? null;
      $extensionName = $validated['extension_name'] ?? null;

      $fullname = $firstName;
      if (!empty($middleInitial)) {
        $mi = rtrim($middleInitial, '.');
        $fullname .= ' ' . $mi . '.';
      }
      $fullname .= ' ' . $lastName;
      if (!empty($extensionName)) {
        $fullname .= ' ' . trim($extensionName);
      }
      $validated['fullname'] = $fullname;
      $validated['mname'] = $middleInitial;
      $validated['extension'] = $extensionName;

      // Remove the separate name keys before mass-assignment
      unset($validated['first_name'], $validated['last_name'], $validated['middle_initial'], $validated['extension_name']);

      // Only student accounts have academic fields
      if ($validated['account_type'] === 'student' && !empty($validated['course_id'])) {
        $course = Course::find($validated['course_id']);
        $validated['course'] = $course?->course_code;
      } else {
        $validated['course'] = null;
      }

      // Non-student roles: force academic fields to null
      if ($validated['account_type'] !== 'student') {
        $validated['course_id'] = null;
        $validated['year_level'] = null;
      }

      // Staff roles: also force student_id to null
      $staffRoles = ['admin', 'dean', 'registrar', 'sec_osa', 'prog_coor', 'psg_officer'];
      if (in_array($validated['account_type'], $staffRoles)) {
        $validated['student_id'] = null;
      }

      // Update the account
      $account->update($validated);

      // Sync status and name to users table
      User::where('email', $account->email)->update([
        'status' => $validated['status'],
        'firstname' => $firstName,
        'lastname' => $lastName,
        'middlename' => $middleInitial,
        'suffix_name' => $extensionName,
      ]);

      return redirect()->route('admin.AddAccount')
        ->with('success', 'Account updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->route('admin.AddAccount')
        ->withErrors($e->errors())
        ->with('error', 'Validation failed. Please check the form data.');

    } catch (\Exception $e) {
      return redirect()->route('admin.AddAccount')
        ->with('error', 'Error updating account: ' . $e->getMessage());
    }
  }

  /**
   * Delete account
   */
  public function deleteAccount($id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      // Prevent deletion of the current admin user
      if (Auth::id() === $account->id) {
        return redirect()->route('admin.AddAccount')
          ->with('error', 'You cannot delete your own account!');
      }

      // Store account name for success message
      $accountName = $account->fullname;

      // Delete the account
      $account->delete();

      return redirect()->route('admin.AddAccount')
        ->with('success', "Account for '{$accountName}' has been deleted successfully!");

    } catch (\Exception $e) {
      return redirect()->route('admin.AddAccount')
        ->with('error', 'Error deleting account: ' . $e->getMessage());
    }
  }

  /**
   * Import users from CSV file
   */
  public function importUsers(ImportUsersRequest $request)
  {

    try {
      $file = $request->file('csv_file');
      $path = $file->getRealPath();
      $data = array_map('str_getcsv', file($path));

      // Remove header row
      $header = array_shift($data);

      // Validate headers
      if (count($header) < 8) {
        return redirect()->back()->with('error', 'CSV file must have 8 columns: student_id, first_name, middle_initial, last_name, extension_name, department, course_year, email');
      }

      $result = $this->accountService->importUsersFromCsv($data);

      $message = "Import completed! {$result['successCount']} students imported successfully.";
      if ($result['errorCount'] > 0) {
        $message .= " {$result['errorCount']} errors occurred.";
      }

      if (!empty($result['errors'])) {
        $errorMessage = implode("\n", array_slice($result['errors'], 0, 10));
        if (count($result['errors']) > 10) {
          $errorMessage .= "\n... and " . (count($result['errors']) - 10) . " more errors.";
        }
        return redirect()->back()->with('import_result', $message)->with('import_errors', $errorMessage);
      }

      return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Error processing CSV file: ' . $e->getMessage());
    }
  }

  /**
   * Download CSV template for importing users
   */
  public function downloadTemplate()
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="student_import_template.csv"',
    ];

    $csvData = [
      ['student_id', 'first_name', 'middle_initial', 'last_name', 'extension_name', 'department', 'course_year', 'email'],
      ['2024-001', 'JUAN', 'D', 'CRUZ', 'JR', 'SITE', 'BSIT 1st Year', 'juan.cruz@spup.edu.ph'],
      ['2024-002', 'MARIA', 'S', 'GARCIA', '', 'SASTE', 'BS Psych 2nd Year', 'maria.garcia@spup.edu.ph'],
      ['2024-003', 'JOSE', '', 'RIZAL', '', 'SBAHM', 'BSA 3rd Year', 'jose.rizal@spup.edu.ph'],
      ['2024-004', 'ANNA', 'M', 'SANTOS', '', 'SNAHS', 'BSN 4th Year', 'anna.santos@spup.edu.ph'],
      ['2024-005', 'MARK', 'J', 'DELA CRUZ', '', 'SITE', 'BS CpE 2nd Year', 'mark.delacruz@spup.edu.ph'],
    ];

    $callback = function() use ($csvData) {
      $file = fopen('php://output', 'w');
      foreach ($csvData as $row) {
        fputcsv($file, $row);
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  /**
   * Convert a student account to alumni
   */
  public function convertToAlumni($id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      if ($account->account_type !== 'student') {
        return redirect()->route('admin.AddAccount', ['tab' => 'list'])
          ->with('error', 'Only student accounts can be marked as graduated.');
      }

      // Block graduating inactive students
      if ($account->status !== 'active') {
        return redirect()->route('admin.AddAccount', ['tab' => 'list'])
          ->with('error', 'This student account is inactive and cannot be marked as graduated.');
      }

      $accountName = $account->fullname;

      // Update academic status only — keep account_type as 'student'
      $account->update([
        'academic_status' => 'Course Completed',
        'graduated_at' => now(),
      ]);

      return redirect()->route('admin.AddAccount', ['tab' => 'list'])
        ->with('success', "Student '{$accountName}' has been marked as graduated successfully.");

    } catch (\Exception $e) {
      return redirect()->route('admin.AddAccount', ['tab' => 'list'])
        ->with('error', 'Error converting account: ' . $e->getMessage());
    }
  }
}
