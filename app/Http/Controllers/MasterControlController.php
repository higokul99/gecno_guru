<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\PhonePeTransaction;
use Illuminate\Support\Facades\Schema;

class MasterControlController extends Controller
{
    /**
     * Show the superadmin login page.
     */
    public function showLogin()
    {
        if (Auth::check() && in_array(Auth::user()->user_type, ['admin', 'manager'])) {
            return redirect()->route('mastercontrol.dashboard');
        }
        return view('mastercontrol.login');
    }

    /**
     * Handle superadmin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (in_array(Auth::user()->user_type, ['admin', 'manager'])) {
                $request->session()->regenerate();
                return redirect()->intended(route('mastercontrol.dashboard'));
            }
            
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. You do not have permission to access this panel.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show the superadmin dashboard.
     */
    public function dashboard()
    {
        // Safely check for table existence before querying to avoid SQL errors
        $hasTransactionTable = Schema::hasTable('phone_pe_transactions');
        
        $stats = [
            'total_users' => User::count(),
            'total_bookings' => Booking::count(),
            'total_transactions' => $hasTransactionTable ? PhonePeTransaction::count() : 0,
            'recent_users' => User::latest()->take(5)->get(),
        ];

        // RBAC: Manager can't see transactions
        if (Auth::user()->user_type === 'manager') {
            $stats['total_transactions'] = 'Restricted';
        }

        return view('mastercontrol.dashboard', compact('stats'));
    }

    /**
     * Handle superadmin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('mastercontrol.login');
    }

    /**
     * Display a listing of users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Protect the primary superadmin from being listed
        $query->where('email', '!=', 'gecnoguru2020@gmail.com');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('mastercontrol.users', compact('users'));
    }

    /**
     * Show detailed user information.
     */
    public function showUser(User $user)
    {
        if ($user->email === 'gecnoguru2020@gmail.com') {
            return redirect()->route('mastercontrol.users')->with('error', 'Access denied to protected account.');
        }

        // RBAC: Manager can't see detailed user actions page if they are not allowed to manage
        // But the requirement says "manager type account only can see user (not action)"
        // So we allow showing the user, but will hide actions in the view
        
        $user->load(['bookings.sessionType', 'transactions']);
        
        return view('mastercontrol.show_user', compact('user'));
    }

    /**
     * Toggle user status (Block/Unblock).
     */
    public function toggleUserStatus(User $user)
    {
        if (Auth::user()->user_type !== 'admin') {
            return back()->with('error', 'Access denied. You do not have permission to perform this action.');
        }

        if ($user->id === Auth::id() || $user->email === 'gecnoguru2020@gmail.com') {
            return back()->with('error', 'This account is protected and cannot be modified.');
        }

        // status 0 = active, 1 = blocked
        $user->status = $user->status == 0 ? 1 : 0;
        $user->save();

        $statusLabel = $user->status == 0 ? 'unblocked' : 'blocked';
        return back()->with('success', "User {$user->name} has been {$statusLabel}.");
    }

    /**
     * Delete user and all related data.
     */
    public function deleteUser(User $user)
    {
        if (Auth::user()->user_type !== 'admin') {
            return back()->with('error', 'Access denied. You do not have permission to perform this action.');
        }

        if ($user->id === Auth::id() || $user->email === 'gecnoguru2020@gmail.com') {
            return back()->with('error', 'This account is protected and cannot be deleted.');
        }

        $userName = $user->name;
        
        // Explicitly delete all related data to ensure "Full Data Deletion"
        $user->bookings()->delete();
        $user->transactions()->delete();
        $user->resumePersonal()->delete();
        $user->resumeExperience()->delete();
        $user->resumeEducation()->delete();
        $user->resumeSkill()->delete();
        $user->resumeCertification()->delete();
        $user->resumeProject()->delete();
        $user->coverPersonal()->delete();
        $user->coverRecipient()->delete();
        $user->coverBody()->delete();
        
        $user->delete();

        return redirect()->route('mastercontrol.users')->with('success', "User {$userName} and all their data have been permanently deleted.");
    }

    /**
     * Display a listing of bookings.
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'sessionType']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(10);

        return view('mastercontrol.bookings', compact('bookings'));
    }

    /**
     * Display a listing of transactions.
     */
    public function transactions(Request $request)
    {
        if (Auth::user()->user_type !== 'admin') {
            return redirect()->route('mastercontrol.dashboard')->with('error', 'Access denied.');
        }

        $query = PhonePeTransaction::with('user');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('merchant_transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $transactions = $query->latest()->paginate(10);

        return view('mastercontrol.transactions', compact('transactions'));
    }

    /**
     * API: Get paginated users.
     */
    public function apiUsers(Request $request)
    {
        $users = User::where('email', '!=', 'gecnoguru2020@gmail.com')
                    ->latest()
                    ->paginate($request->get('per_page', 10));
        return response()->json($users);
    }
}
