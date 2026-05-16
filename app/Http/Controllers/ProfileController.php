<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Show profile edit form
    public function edit()
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        return view('profile.edit', compact('user'));
    }

    // Update personal information (name, email)
    public function updatePersonal(Request $request)
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update session name if changed
        session(['user_name' => $user->name]);

        return redirect()->route('profile.edit')->with('success', __('messages.profile_updated_success'));
    }

    // Update financial settings (income, savings)
    public function updateFinancial(Request $request)
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $validator = Validator::make($request->all(), [
            'income' => 'required|integer|min:0',
            'savings_percentage' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->income = $request->income;
        $user->savings_percentage = $request->savings_percentage;
        $user->save();

        return redirect()->route('profile.edit')->with('success', __('messages.profile_updated_success'));
    }

    // Update category budgets
    public function updateCategories(Request $request)
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $validator = Validator::make($request->all(), [
            'budget_transportation' => 'required|integer|min:0|max:100',
            'budget_food' => 'required|integer|min:0|max:100',
            'budget_home_utilities' => 'required|integer|min:0|max:100',
            'budget_entertainment' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate that category budgets sum to 100
        $total = $request->budget_transportation + 
                $request->budget_food + 
                $request->budget_home_utilities + 
                $request->budget_entertainment;
        
        if ($total != 100) {
            return back()->withErrors([
                'budget_total' => 'Category budget percentages must total exactly 100%. Current total: ' . $total . '%'
            ])->withInput();
        }

        $user->budget_transportation = $request->budget_transportation;
        $user->budget_food = $request->budget_food;
        $user->budget_home_utilities = $request->budget_home_utilities;
        $user->budget_entertainment = $request->budget_entertainment;
        $user->save();

        return redirect()->route('profile.edit')->with('success', __('messages.profile_updated_success'));
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = User::find(session('user_id'));
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('messages.current_password_incorrect')])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.edit')->with('success', __('messages.password_updated_success'));
    }
}