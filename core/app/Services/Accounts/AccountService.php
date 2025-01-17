<?php
namespace App\Services\Accounts;

use App\Models\Customer;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class AccountService
{
    // Check the role of the account (customer or coach) and execute the logic accordingly
    public function createAccount($data, $role)
    {
        if ($role === 'customer') {
            // Logic for creating a customer account
            $validator = \Validator::make($data, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email|max:255',
                'password' => 'required|min:6',
                'gender' => 'required|in:M,F',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'profile_pic' => 'nullable|string|max:255', // Assuming it's a URL or file path
                'inputText' => 'required|string', // User's CAPTCHA input
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Verify CAPTCHA
            $response = Http::post('http://localhost:3001/verify-captcha', [
                'inputText' => $data['inputText'],
                'captchaText' => $data['captchaText'], // Hidden field
            ]);

            $result = $response->json();

            if (!$result['success']) {
                return redirect()->back()->withErrors(['captcha' => 'Captcha verification failed.']);
            }

            // Create the customer record
            $customer = Customer::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => \Hash::make($data['password']),
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'],
                'birth_date' => $data['birth_date'],
                'profile_pic' => $data['profile_pic'] ?? null,
                'creation_date' => now(),
            ]);
        } elseif ($role === 'coach') {
            // Logic for creating a coach account
            $examResult = $this->CoachExamResultCheck(request()); // Pass the request to check the exam

            if ($examResult instanceof \Illuminate\Http\RedirectResponse) {
                // If it returns a redirect response, return it
                return $examResult;
            }

        } else {
            throw new \Exception('Invalid role.');
        }
    }

    public function updateAccount($id, $data, $role, \Illuminate\Http\Request $request)
    {
        // Define validation rules for account data
        $validatedData = [];

        if ($role === 'customer') {
            // Validate the data for a customer
            $validatedData = validator($data, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255',
                'password' => 'nullable|min:6', // Password is optional for update
                'gender' => 'required|in:M,F',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'required|date',
                // 'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile picture is optional
            ])->validate();

            // Find the customer by their ID
            $customer = Customer::findOrFail($id);

            // If there's a profile picture uploaded, handle it
            if ($request->hasFile('profile_pic')) {
                // Store the image in Dropbox
                $path = $request->file('profile_pic')->store('profile_pictures', 'dropbox');

                // Get the public URL for the image
                $imageUrl = Storage::disk('dropbox')->url($path);

                // Save the image URL to the customer's profile
                $customer->profile_pic = $imageUrl;
            }

            if (!empty($data['password'])) {
                $validatedData['password'] = Hash::make($data['password']);
            }

            // Update the customer with the validated data
            $customer->update($validatedData);

        } elseif ($role === 'coach') {
            // Validate the data for a coach
            $validatedData = validator($data, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255',
                'password' => 'nullable|min:6', // Password is optional for update
                'gender' => 'required|in:M,F',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'required|date',
                // 'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile picture is optional
            ])->validate();

            // Find the coach by their ID
            $coach = Coach::findOrFail($id);

            // If there's a profile picture uploaded, handle it
            if ($request->hasFile('profile_pic')) {
                // Store the image in Dropbox
                $path = $request->file('profile_pic')->store('profile_pictures', 'dropbox');

                // Get the public URL for the image
                $imageUrl = Storage::disk('dropbox')->url($path);

                // Save the image URL to the coach's profile
                $coach->profile_pic = $imageUrl;
            }

            if (!empty($data['password'])) {
                $validatedData['password'] = Hash::make($data['password']);
            }

            // Update the coach with the validated data
            $coach->update($validatedData);
        } else {
            throw new \InvalidArgumentException('Invalid role provided.');
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function resetPassword($id, $role)
    {
        // Generate a new random password
        $newPassword = Str::random(8); // You can customize the length

        // Find the user based on the role
        if ($role === 'customer') {
            $user = Customer::find($id);
        } elseif ($role === 'coach') {
            $user = Coach::find($id);
        } else {
            // Handle invalid role case
            return false;
        }



        if ($user) {
            // Update the password in the database
            $user->password = Hash::make($newPassword);
            $user->save();

            // Send the new password to the user's email
            Mail::to($user->email)->send(new \App\Mail\PasswordReset($newPassword));

            return true; // Indicate success
        }

        return false; // User not found
    }

    // SimpleXML Parser Logic
    public function parseQuestions()
    {
        $xml = simplexml_load_file(storage_path('app/xml/exam-questions.xml')) or die("Error: Cannot create object");

        $questions = [];
        foreach ($xml->question as $question) {
            $answers = [];
            foreach ($question->answer as $answer) {
                $answers[] = [
                    'text' => (string) $answer,
                    'correct' => (string) $answer['correct']
                ];
            }
            $questions[] = [
                'id' => (string) $question['id'],
                'text' => (string) $question->text,
                'answers' => $answers
            ];
        }

        return $questions;
    }

    // XSLT Transformation Logic
    public function transformXMLWithXSLT()
    {
        $xml = new \DOMDocument();
        $xml->load(storage_path('app/xml/exam-questions.xml'));

        $xsl = new \DOMDocument();
        $xsl->load(storage_path('app/xml/exam-questions.xsl')); // Path to your XSLT file

        $proc = new \XSLTProcessor();
        $proc->importStylesheet($xsl);

        return $proc->transformToXML($xml);
    }

    public function migrateCustomerToCoach()
    {
        // Retrieve customer data
        $customer = Auth::guard('customer')->user();
        $customerId = $customer->customer_id;

        // Insert customer data into coaches table
        Coach::create([
            'coach_id' => $customer->customer_id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'password' => $customer->password,
            'email' => $customer->email,
            'gender' => $customer->gender,
            'phone_number' => $customer->phone_number,
            'birth_date' => $customer->birth_date,
            'creation_date' => $customer->creation_date,
            'profile_pic' => $customer->profile_pic,
            'description' => "None",  // This can be updated later
            'coach_status' => 'A'    // 'A' for Active or similar status
        ]);

        // Delete customer record from customers table
        $customer->delete();
    }
}
