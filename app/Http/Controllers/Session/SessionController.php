<?php  

namespace App\Http\Controllers\Session;  

use App\Models\Session\AcademicSession;  
use Illuminate\Http\Request;  
use App\Http\Controllers\Controller;
  

class SessionController extends Controller 
{     
 

public function index(Request $request)
    {
        // Get per_page value from request, default to 10
        $perPage = $request->input('per_page', 10);
        
        // Validate per_page to only allow specific values
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Get search query
        $search = $request->input('search', '');

        // Build the query - use AcademicSession instead of Session
        $query = AcademicSession::query();

        // Apply search filter if search term exists
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Get paginated sessions
        $sessions = $query->orderBy('created_at', 'desc')
                          ->paginate($perPage)
                          ->appends([
                              'search' => $search,
                              'per_page' => $perPage
                          ]);

        return view('session.session', compact('sessions', 'search'));
    } 

    /** GET /sessions/create */     
    public function create()     
    {         
        // Show form for creating a new session
        return view('session.create', ['session' => new AcademicSession()]);     
    }      

    /** POST /sessions */     
    public function store(Request $request)     
    {         
        // Validate input
        $validated = $request->validate([         
            'name' => [             
                'required',             
                'string',             
                'min:3',             
                'max:100',             
                'unique:academic_sessions,name' // unique session names only         
            ],         
            'start_date' => [             
                'required',             
                'date',             
                'after:today' // must be after today         
            ],         
            'end_date' => [             
                'required',             
                'date',             
                'after:start_date' // must be after start date         
            ]     
        ], [         
            'name.required' => 'Session name is required.',         
            'name.min' => 'Session name must be at least 3 characters.',         
            'name.max' => 'Session name cannot exceed 100 characters.',         
            'name.unique' => 'A session with this name already exists.',         
            'start_date.required' => 'Start date is required.',         
            'start_date.date' => 'Please enter a valid start date.',         
            'start_date.after' => 'Start date must be after today.',         
            'end_date.required' => 'End date is required.',         
            'end_date.date' => 'Please enter a valid end date.',         
            'end_date.after' => 'End date must be after the start date.'     
        ]);          

        // Only one active session allowed         
        if (AcademicSession::where('status', 'active')->exists()) {             
            return back()->with('error', 'Cannot create session: Limit of 1 active session reached.');         
        }          

        // Default to active when creating         
        $validated['status'] = 'active';          
        AcademicSession::create($validated);          

        return redirect()->route('sessions.index')->with('success', 'Session created successfully.');     
    }      

    /** GET /sessions/{session} */     
    public function show(AcademicSession $session)     
    {         
      return response()->json([
    'id'         => $session->_id,
    'name'       => $session->name,
    'start_date' => $session->start_date,
    'end_date'   => $session->end_date,
    'status'     => $session->status,
]);
     
    }      

    /** PUT /sessions/{session} */     
    public function update(Request $request, AcademicSession $session)     
    {         
        // Basic validation rules         
        $rules = [             
            'name' => 'required|string|min:3|max:100|unique:academic_sessions,name,' . $session->_id,
            'start_date' => ['required', 'date'],             
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],             
            'status'     => 'required|in:active,deactive',         
        ];          

        // If session hasn't started yet, require future date         
        if ($session->start_date > now()) {             
            $rules['start_date'][] = 'after:today';         
        }          

        $validated = $request->validate($rules, [             
            'name.required' => 'Session name is required.',             
            'name.unique' => 'A session with this name already exists.',             
            'start_date.after' => 'Start date must be after today.',             
            'end_date.after_or_equal' => 'End date must be after or equal to the start date.'         
        ]);          

        // If making active, ensure no other active session exists         
        if ($validated['status'] === 'active') {             
            $query = AcademicSession::where('status', 'active')->where('id', '!=', $session->id);             
            if ($query->exists()) {                 
                return back()->with('error', 'Another active session already exists. Deactivate it first.');             
            }         
        }          

        // Save updates         
        $session->update($validated);         
        return redirect()->route('sessions.index')->with('success', 'Session updated successfully.');     
    }      

    /** DELETE /sessions/{session} */     
    public function destroy(AcademicSession $session)     
    {         
        // Delete session         
        $session->delete();         
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully.');     
    }      

    /** POST /sessions/{session}/end */     
    public function end(AcademicSession $session)     
    {         
        // Mark session as inactive         
        $session->status = 'deactive';         
        $session->save();          

        return redirect()->route('sessions.index')->with('success', 'Session ended successfully.');     
    } 
}  
