<?php

namespace App\Http\Controllers\Session;

use App\Models\Session\AcademicSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    /** GET /sessions */
    public function index()
    {
        $sessions = AcademicSession::orderByDesc('created_at')->get();
        return view('session.session', compact('sessions'));
    }

    /** GET /sessions/create */
    public function create()
    {
        return view('session.create', ['session' => new AcademicSession()]);
    }

    /** POST /sessions */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // Only one active session allowed
        if (AcademicSession::where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot create session: Limit of 1 active session reached.');
        }

        $validated['status'] = 'active'; // default status
        AcademicSession::create($validated);

        return redirect()->route('sessions.index')->with('success', 'Session created successfully.');
    }

    /** GET /sessions/{session} */
    public function show(AcademicSession $session)
    {
        return response()->json([
            'id'         => $session->_id ?? $session->id,
            'name'       => $session->name,
            'start_date' => $session->start_date,
            'end_date'   => $session->end_date,
            'status'     => $session->status,
        ]);
    }

    /** PUT /sessions/{session} */
    public function update(Request $request, AcademicSession $session)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:active,deactive',
        ]);

        // If setting active, ensure no other active session exists
        if ($validated['status'] === 'active') {
            $query = AcademicSession::where('status', 'active')->where('id', '!=', $session->id);
            if ($query->exists()) {
                return back()->with('error', 'Another active session already exists. Deactivate it first.');
            }
        }

        $session->update($validated);
        return redirect()->route('sessions.index')->with('success', 'Session updated successfully.');
    }

    /** DELETE /sessions/{session} */
    public function destroy(AcademicSession $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully.');
    }

    /** POST /sessions/{session}/end */
    public function end(AcademicSession $session)
    {
        $session->status = 'deactive';
        $session->save();

        return redirect()->route('sessions.index')->with('success', 'Session ended successfully.');
    }
}
