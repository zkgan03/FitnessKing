<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Coach;

use App\Http\Facades\AccountFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    public function showRegisterForm()
    {
        // Call the Node.js CAPTCHA service to generate CAPTCHA
        $response = Http::get('http://localhost:3001/generate-captcha');
        $captcha = $response->json();

        return view('authorization.register', ['captchaSvg' => $captcha['captchaSvg'], 'captchaText' => $captcha['captchaText']]);
    }

    public function showLoginForm()
    {
        return view('authorization.login');
    }

    public function showResetForm()
    {
        // Call the Node.js CAPTCHA service to generate CAPTCHA
        $response = Http::get('http://localhost:3001/generate-captcha');
        $captcha = $response->json();

        return view('authorization.reset-pass');
    }

    public function showExamForm()
    {
        // Use the XSLT service to transform XML
        $transformedContent = AccountFacade::transformXMLWithXSLT();

        // Return the view with transformed HTML content
        return view('authorization.coach-exam', ['transformedContent' => $transformedContent]);
    }

    public function showProfile()
    {
        if (Auth::guard('customer')->check()) {
            // Grab data from the 'customer' guard
            $user = Auth::guard('customer')->user();
        }

        if (Auth::guard('coach')->check()) {
            // Grab data from the 'customer' guard
            $user = Auth::guard('coach')->user();
        }

        // Pass the user data to the view
        return view('account.index', compact('user'));
    }

    public function showEditForm()
    {
        if (Auth::guard('customer')->check()) {
            // Grab data from the 'customer' guard
            $user = Auth::guard('customer')->user();
        }

        if (Auth::guard('coach')->check()) {
            // Grab data from the 'customer' guard
            $user = Auth::guard('coach')->user();
        }

        // Pass the user data to the view
        return view('account.edit', compact('user'));
    }

    public function createCustomer(Request $request)
    {
        $data = $request->all();

        // Default role to 'customer' if not provided
        $role = $request->input('role', 'customer');

        AccountFacade::createAccount($data, $role);
        return response()->json(['message' => 'Account created successfully for ' . $role . '!']) . redirect()->route('login');
    }

    public function createCoach(Request $request)
    {
        // Check if a customer is logged in
        $customer = Auth::guard('customer')->user();

        if ($customer) {
            // The logged-in customer data is available here
            $customerData = $customer->toArray();  // Convert customer object to array if needed

            // Proceed with coach creation logic
            $data = $request->all();

            // Default role to 'coach' if not provided
            $role = $request->input('role', 'coach');

            // Creating the account
            AccountFacade::createAccount($data, $role);

            // Return a success response and include customer data
            return response()->json([
                'message' => 'Account created successfully for ' . $role . '!',
                'customer' => $customerData, // Include the logged-in customer data in the response
            ])->with('success', 'Account created')->redirect()->route('login');
        } else {
            // If no customer is logged in, handle accordingly
            return redirect()->route('login')->with('error', 'You need to log in first.');
        }
    }

    public function CoachExamResultCheck(Request $request)
    {
        $score = 7;
        // dd($request->all()); // Debug to check all inputs

        // Parse the questions and iterate over them
        foreach (AccountFacade::parseQuestions() as $question) {
            $questionId = 'question_' . $question['id']; // Input name format
            // dd($questionId); // Debug to check question ID

            $selectedAnswers = $request->input($questionId, []); // Get selected answers as an array

            // Debug to check selected answers
            // dd($selectedAnswers); // This will show the selected answers for the current question

            // Collect correct answers for this question
            $correctAnswers = [];
            foreach ($question['answers'] as $answer) {
                if ($answer['correct'] === true) {
                    $correctAnswers[] = $answer['text']; // Store correct answer text
                }
            }

            // Sort both selected and correct answers for comparison
            // sort(array: $selectedAnswers);
            // sort($correctAnswers);

            // Compare selected answers with correct ones
            if ($selectedAnswers === $correctAnswers) {
                $score++; // Increment score if all correct answers are selected
            }

        }

        // dd($score); // Debug to check the final score
        $requiredScore = 7;

        // If the score meets the requirement
        if ($score >= $requiredScore) {
            // Migrate customer to coach
            AccountFacade::migrateCustomerToCoach(auth()->user()->customerID);

            // Redirect to success page or dashboard
            return redirect()->route('home')->with('success', 'You are now registered as a coach.');
        } else {
            // Provide feedback to the user
            return redirect()->back()->with('error', 'You did not answer enough questions correctly.');
        }
    }


    public function update($id, Request $request)
    {
        $data = $request->all();
        $role = null; // Initialize role variable

        if (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user(); // Use -> instead of :
            $role = $request->input('role', 'customer');
        } elseif (Auth::guard('coach')->check()) {
            $user = Auth::guard('coach')->user(); // Use -> instead of :
            $role = $request->input('role', 'coach');
        }

        if (!$role) {
            // Handle case where no role was found (optional)
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        AccountFacade::updateAccount($id, $data, $role, $request);
        return response()->json(['message' => 'Account updated successfully for ' . $role . '!']);
    }

    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            session(['role' => 'customer']);
            return redirect()->route('home');
        }

        if (Auth::guard('coach')->attempt($request->only('email', 'password'))) {
            session(['role' => 'coach']);
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $email = $request->input('email'); // Get email from the request

        // Check if the email exists in the Customer table
        $customer = Customer::where('email', $email)->first();

        // Check if the email exists in the Coach table
        $coach = Coach::where('email', $email)->first();

        if (!$customer && !$coach) {
            return response()->json(['message' => 'Email does not exist.'], 404);
        }

        // Determine the role based on which user was found
        $role = $customer ? 'customer' : 'coach';
        $id = $customer ? $customer->customer_id : $coach->coach_id; // Get the user ID

        $result = AccountFacade::resetPassword($id, $role);

        if ($result) {
            return response()->json(['message' => 'Password reset email sent successfully.']);
        } else {
            return response()->json(['message' => 'Failed to reset password.'], 400);
        }
    }

    public function logout()
    {
        if (Auth::guard('customer')->check()) {
            // Logout using the 'customer' guard
            Auth::guard('customer')->logout();
        }

        if (Auth::guard('coach')->check()) {
            // Logout using the 'customer' guard
            Auth::guard('coach')->logout();
        }

        // Optionally clear the session for role
        session()->forget('role');

        // Redirect to the login page
        return redirect()->route('login');
    }
}



