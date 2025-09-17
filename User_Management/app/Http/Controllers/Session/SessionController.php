<?php
namespace App\Http\Controllers\Session;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Session\AcademicSession;


class SessionController extends Controller
{
    // Show all sessions
    public function index()
    {
        // order by created_at descending so newest appear first
        $sessions = AcademicSession::orderBy('created_at', 'desc')->get();

        // pass to view 'session' (resources/views/session.blade.php)
        return view('session.session', compact('sessions'));
    }
 public function create()
    {
        // Make sure you have resources/views/inquiries/create.blade.php
        return view('session.create', ['session' => new AcademicSession()]);
    }
    // Add session from modal form
    public function store(Request $request)
    {
        // check if any active session already exists
        $activeExists = AcademicSession::where('status', 'active')->exists();

        if ($activeExists) {
            return back()->with('error', 'Cannot create session: Limit of 1 active session reached.');
        }

        AcademicSession::create([
            'name'       => $request->name,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => 'active', // default active on creation
        ]);

        return back()->with('success', 'Session created successfully.');
    }

    // Show details
    public function show($id)
    {
        \Log::info('Show session called with ID: ' . $id);
        
        // Try finding by standard id first
        $session = AcademicSession::find($id);
        
        // If not found, try finding by MongoDB _id
        if (!$session) {
            $session = AcademicSession::where('_id', $id)->first();
        }

        if (!$session) {
            \Log::error('Session not found with ID: ' . $id);
            return response()->json(['success' => false, 'error' => 'Session not found'], 404);
        }

        // Convert to array and normalize the ID
        $data = $session->toArray();
        
        \Log::info('Session found, raw data:', $data);
        
        // Ensure we always have an 'id' field for JavaScript
        if (isset($data['_id'])) {
            if (is_array($data['_id']) && isset($data['_id']['$oid'])) {
                $data['id'] = $data['_id']['$oid'];
            } elseif (is_object($data['_id'])) {
                // Handle MongoDB ObjectId object
                $data['id'] = (string) $data['_id'];
            } else {
                $data['id'] = (string) $data['_id'];
            }
        } elseif (!isset($data['id'])) {
            // Fallback: use the passed ID
            $data['id'] = $id;
        }
        
        \Log::info('Final data being returned:', $data);

        return response()->json($data);
    }

    // Update session
    public function update(Request $request, $id)
    {
        try {
            // Try finding by standard id first
            $session = AcademicSession::find($id);
            
            // If not found, try finding by MongoDB _id
            if (!$session) {
                $session = AcademicSession::where('_id', $id)->first();
            }
            
            if (!$session) {
                return response()->json(['success' => false, 'error' => 'Session not found.'], 404);
            }

            $data = $request->only(['name', 'start_date', 'end_date', 'status']);

            // Normalize status to lowercase for consistency
            if (isset($data['status'])) {
                $data['status'] = strtolower($data['status']); // active / deactive
            }

            // If trying to set active, ensure no other session is active
            if (isset($data['status']) && $data['status'] === 'active') {
                $query = AcademicSession::where('status', 'active');
                
                // Exclude current session from the check
                if (isset($session->_id)) {
                    $query->where('_id', '!=', $session->_id);
                } else {
                    $query->where('id', '!=', $session->id);
                }

                if ($query->exists()) {
                    return response()->json(['success' => false, 'error' => 'Another active session already exists. Deactivate it first.'], 422);
                }
            }

            $session->update($data);

            return response()->json(['success' => true, 'msg' => 'Session updated successfully.']);
            
        } catch (\Throwable $e) {
            \Log::error('Session update error: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine());
            return response()->json(['success' => false, 'error' => 'Server error: '.$e->getMessage()], 500);
        }
    }

    // End session
    public function end($id)
    {
        // Try finding by standard id first
        $session = AcademicSession::find($id);
        
        // If not found, try finding by MongoDB _id
        if (!$session) {
            $session = AcademicSession::where('_id', $id)->first();
        }

        if (!$session) {
            return redirect()->route('sessions.index')->with('error', 'Session not found.');
        }

        $session->status = 'deactive';
        $session->save();

        return redirect()->route('sessions.index')->with('success', 'Session ended successfully.');
    }
}