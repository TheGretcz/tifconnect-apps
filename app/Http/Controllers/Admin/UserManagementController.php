<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Exports\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('isp_name', 'like', "%{$search}%")
                    ->orWhere('isp_brand', 'like', "%{$search}%")
                    ->orWhere('pic_isp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Generate a unique 6-digit username
     */
    private function generateUsername(): string
    {
        do {
            $username = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('username', $username)->exists());

        return $username;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:Super Admin,Admin,ISP',
            'pic_isp' => 'nullable|string|max:255',
            'isp_brand' => 'nullable|string|max:255',
            'isp_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
        ]);

        $validated['username'] = $this->generateUsername();
        $validated['password'] = Hash::make($validated['password'] ?? 'TIFConnect2026');

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan. Username: '.$validated['username']);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:Super Admin,Admin,ISP',
            'pic_isp' => 'nullable|string|max:255',
            'isp_brand' => 'nullable|string|max:255',
            'isp_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->user_id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function export()
    {
        return UserExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        try {
            UserImport::import($request->file('file'));

            return redirect()->back()->with('success', 'Data user berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }

    public function clearAll()
    {
        if (auth()->user()->role !== 'Super Admin') {
            return redirect()->route('admin.users.index')->with('error', 'Unauthorized action.');
        }

        // Only delete non-Super Admin users
        User::where('role', '!=', 'Super Admin')->delete();

        return redirect()->route('admin.users.index')->with('success', 'Semua data ISP/Admin berhasil dihapus (Super Admin tetap dipertahankan).');
    }

    public function downloadTemplate()
    {
        return UserTemplateExport::download();
    }
}
