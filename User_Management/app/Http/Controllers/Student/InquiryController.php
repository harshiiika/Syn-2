<?php 

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student\Inquiry;

class InquiryController extends Controller
{
    /** GET /inquiries */
    public function index()
    {
        $inquiries = Inquiry::orderByDesc('created_at')->paginate(10);
        return view('inquiries.index', compact('inquiries'));
    }

    /** (Optional) GET /inquiries/list */
    public function list(Request $request)
    {
        $rows = Inquiry::orderByDesc('created_at')->get();
        return response()->json(['data' => $rows]);
    }

    /** GET /inquiries/create */
    public function create()
    {
        // Make sure you have resources/views/inquiries/create.blade.php
        return view('inquiries.create', ['inquiry' => new Inquiry()]);
    }

    /** POST /inquiries */
    public function store(Request $request)
    {
        $validated = $this->rules($request);
        Inquiry::create($validated);

        return redirect()
            ->route('inquiries.index')
            ->with('status', 'Inquiry created successfully.');
    }

    /** GET /inquiries/{inquiry} */
    public function show(Inquiry $inquiry)
    {
        // Make sure you have resources/views/inquiries/view.blade.php
        return view('inquiries.view', compact('inquiry'));
    }

    /** GET /inquiries/{inquiry}/edit */
    public function edit(Inquiry $inquiry)
    {
        // Make sure you have resources/views/inquiries/edit.blade.php
        return view('inquiries.edit', compact('inquiry'));
    }

    /** PUT /inquiries/{inquiry} */
    public function update(Request $request, string $id)
    {
        // ✅ validate input (includes status)
        $data = $request->validate([
            'student_name'       => ['required','string','max:120'],
            'father_name'        => ['required','string','max:120'],
            'father_contact'     => ['required','string','max:20'],
            'father_whatsapp'    => ['nullable','string','max:20'],
            'student_contact'    => ['required','string','max:20'],
            'category'           => ['required','string','max:50'],
            'state'              => ['required','string','max:80'],
            'city'               => ['required','string','max:80'],
            'address'            => ['nullable','string','max:500'],
            'branch_name'        => ['required','string','max:50'],
            'ews'                => ['required','boolean'],
            'service_background' => ['required','boolean'],
            'specially_abled'    => ['required','boolean'],
            'status'             => ['required','in:new,open,closed'], // ✅ include status
        ]);

        // ✅ Find the inquiry by _id and update
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->fill($data);
        $inquiry->save();

        return redirect()
            ->route('inquiries.index')
            ->with('status', 'Inquiry updated successfully.');
    }

    /** DELETE /inquiries/{inquiry} */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()
            ->route('inquiries.index')
            ->with('status', 'Inquiry deleted successfully.');
    }

    /** POST /inquiries/{inquiry}/status */
    public function setStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate(['status' => 'required|string|max:30']);
        $inquiry->status = $request->input('status', 'Pending');
        $inquiry->save();

        return response()->json(['message' => 'status set']);
    }

    /* -------- shared validation -------- */
    protected function rules(Request $request): array
    {
        return $request->validate([
            'student_name'        => 'required|string|max:255',
            'father_name'         => 'required|string|max:255',
            'father_contact'      => 'required|string|max:20',
            'father_whatsapp'     => 'nullable|string|max:20',
            'student_contact'     => 'nullable|string|max:20',

            'category'            => 'required|in:GENERAL,OBC,SC,ST',
    
            'state'               => 'required|string|max:100',
            'city'                => 'required|string|max:100',
            'address'             => 'nullable|string|max:2000',
            'branch_name'         => 'required|string|max:100',

            'ews'                 => 'required|in:yes,no',
            'service_background'  => 'required|in:yes,no',
            'specially_abled'     => 'required|in:yes,no',

            // Optional course fields
            'course_type'         => 'nullable|string|max:100',
            'course_name'         => 'nullable|string|max:150',
            'delivery_mode'       => 'nullable|in:Offline,Online,Hybrid',
            'medium'              => 'nullable|in:Hindi,English,Bilingual',
            'board'               => 'nullable|string|max:50',
            'course_content'      => 'nullable|string|max:100',

            // Optional status
            'status'              => 'nullable|string|max:30',
        ]);
    }
}
