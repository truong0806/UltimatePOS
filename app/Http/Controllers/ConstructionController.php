<?php

namespace App\Http\Controllers;

use App\Construction;
use App\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConstructionController extends Controller
{

    public function index()
    {
        return view('constructions.index');
    }
    public function getConstructions()
    {
        $constructions = Construction::query();

        return DataTables::of($constructions)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">';
                $html .= '<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">' . __('messages.actions') . '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
                $html .= '<ul class="dropdown-menu dropdown-menu-left" role="menu">';

                $html .= '<li><a href="' . action([\App\Http\Controllers\ConstructionController::class, 'view'], [$row->id]) . '" class="view-btn" data-id="' . $row->id . '"><i class="fa fa-eye"></i> ' . __('messages.view') . '</a></li>';

                $html .= '<li><a href="#" class="edit-btn" data-id="' . $row->id . '"><i class="glyphicon glyphicon-edit"></i> ' . __('messages.edit') . '</a></li>';
                $html .= '<li><a href="' . route('constructions.destroy', [$row->id]) . '" class="delete-construction"><i class="fa fa-trash"></i> ' . __('messages.delete') . '</a></li>';

                $html .= '</ul></div>';

                return $html;
            })
            ->editColumn('budget', function ($row) {
                return number_format($row->budget, 2);
            })
            ->editColumn('contact_name', function ($row) {
                return $row->contact ? $row->contact->name : 'none';
            })
            ->editColumn('introducer_name', function ($row) {
                return $row->introducer ? $row->introducer->name : 'none';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action'])
            ->make(true);
    }




    public function showCreateForm()
    {
        $business_id = request()->session()->get('user.business_id');
        $contacts = Contact::contactDropdown($business_id);
        return view('constructions.create', compact('contacts'));
    }

    public function createConstruction(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'contact_id' => 'nullable|exists:contacts,id',
            'introducer_id' => 'nullable|exists:contacts,id',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'total_payment' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
        ]);

        // Create a new Construction record
        Construction::create($validatedData);

        // Return a JSON response
        return redirect()->route('constructions.index')->with('success', 'Construction created successfully!');
    }




    public function deleteConstruction($id)
    {
        $construction = Construction::findOrFail($id);
        $construction->delete();

        return redirect('/constructions');
    }
    public function show($id)
    {
        $construction = Construction::findOrFail($id);
        return view('constructions.show', compact('construction'));
    }
    public function edit($id)
    {
        $construction = Construction::find($id);

        if (!$construction) {
            return response()->json(['error' => 'Construction not found'], 404);
        }

        $customers = Contact::active()->pluck('name', 'id');
        $introducers = Contact::active()->pluck('name', 'id');

        return response()->json([
            'construction' => $construction,
            'customers' => $customers->map(fn ($name, $id) => ['id' => $id, 'name' => $name]),
            'introducers' => $introducers->map(fn ($name, $id) => ['id' => $id, 'name' => $name]),
            'customer_id' => $construction->contact_id,
            'introducer_id' => $construction->introducer_id
        ]);
    }



    public function update(Request $request, $id)
    {
        $construction = Construction::find($id);

        if (!$construction) {
            return response()->json(['error' => 'Construction not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'contact_id' => 'nullable|exists:contacts,id',
            'introducer_id' => 'nullable|exists:contacts,id',
        ]);

        $construction->update($validatedData);

        return response()->json(['success' => 'Construction updated successfully']);
    }




    public function view($id)
    {
        $construction = Construction::find($id);

        if (!$construction) {
            return response()->json(['error' => 'Construction not found'], 404);
        }

        return response()->json([
            'name' => $construction->name,
            'budget' => number_format($construction->budget, 2),
            'start_date' => $construction->start_date,
            'end_date' => $construction->end_date,
            'budget' => number_format($construction->budget, 2),
            'customer_name' => $construction->customer ? $construction->customer->name : 'N/A',
            'introducer_name' => $construction->introducer ? $construction->introducer->name : 'N/A',
        ]);
    }

    public function destroy($id)
    {
        $construction = Construction::findOrFail($id);
        $construction->delete();

        return response()->json(['success' => true]);
    }
}
