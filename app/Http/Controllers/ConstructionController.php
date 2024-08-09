<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Construction;
use App\Contact;
use App\Transaction;
use App\TransactionPayment;
use App\User;
use App\Utils\ContactUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;

class ConstructionController extends Controller
{

    public function index()
    {
        return view('constructions.index');
    }
    public function getConstructionPayments($construction_id)
    {
        $transactionUtil = new \App\Utils\TransactionUtil();
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {
            $payments = TransactionPayment::leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
                ->leftjoin('transaction_payments as parent_payment', 'transaction_payments.parent_id', '=', 'parent_payment.id')
                ->where('transaction_payments.business_id', $business_id)
                ->whereNull('transaction_payments.parent_id')
                ->with(['child_payments', 'child_payments.transaction'])
                ->where('transaction_payments.construction_payment', $construction_id)
                ->select(
                    'transaction_payments.id',
                    'transaction_payments.amount',
                    'transaction_payments.is_return',
                    'transaction_payments.method',
                    'transaction_payments.paid_on',
                    'transaction_payments.payment_ref_no',
                    'transaction_payments.parent_id',
                    'transaction_payments.transaction_no',
                    'transaction_payments.construction_payment',
                    't.invoice_no',
                    't.ref_no',
                    't.type as transaction_type',
                    't.return_parent_id',
                    't.id as transaction_id',
                    'transaction_payments.cheque_number',
                    'transaction_payments.card_transaction_number',
                    'transaction_payments.bank_account_number',
                    'transaction_payments.id as DT_RowId',
                    'parent_payment.payment_ref_no as parent_payment_ref_no'
                )
                ->groupBy('transaction_payments.id')
                ->orderByDesc('transaction_payments.paid_on')
                ->paginate();

            $payment_types = $transactionUtil->payment_types(null, true, $business_id);

            return view('constructions.partials.construction_payments_tab')
                ->with(compact('payments', 'payment_types'));
        }
    }
    public function getConstructions()
    {
        $constructions = Construction::query();

        return DataTables::of($constructions)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">';
                $html .= '<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">' . __('messages.actions') . '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
                $html .= '<ul class="dropdown-menu dropdown-menu-left" role="menu">';

                $html .= '<li><a href="' . route('constructions.show', [$row->id]) . '" class="view-construction" data-id="' . $row->id . '"><i class="fa fa-eye"></i> ' . __('messages.view') . '</a></li>';
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
            ->editColumn('contact_id', function ($row) {
                return $row->contact ? $row->contact->id : 'none';
            })
            ->editColumn('introducer_id', function ($row) {
                return $row->introducer ? $row->introducer->id : 'none';
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


    public function getLedger()
    {
        if (!auth()->user()->can('supplier.view') && !auth()->user()->can('customer.view') && !auth()->user()->can('supplier.view_own') && !auth()->user()->can('customer.view_own')) {
            abort(403, 'Unauthorized action.');
        }
        $contactUtil = new \App\Utils\ContactUtil();
        $transactionUtil = new \App\Utils\TransactionUtil();
        $business_id = request()->session()->get('user.business_id');
        $construction_id = request()->input('construction_id');

        $is_admin = $contactUtil->is_admin(auth()->user());

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $format = request()->format;
        $location_id = request()->location_id;

        $construction = Construction::find($construction_id);
        $contact = Contact::find($construction->contact_id);

        $line_details = $format == 'format_3' ? true : false;

        $ledger_details = $transactionUtil->getLedgerConstructionDetails($construction_id, $contact, $start_date, $end_date, $format, $location_id, $line_details);

        $location = null;
        if (!empty($location_id)) {
            $location = BusinessLocation::where('business_id', $business_id)->find($location_id);
        }
        if (request()->input('action') == 'pdf') {
            $output_file_name = 'Ledger-' . str_replace(' ', '-', $construction->name) . '-' . $start_date . '-' . $end_date . '.pdf';
            $for_pdf = true;
            if ($format == 'format_2') {
                $html = view('constructions.ledger_format_2')
                    ->with(compact('ledger_details', 'construction', 'for_pdf', 'location'))->render();
            } elseif ($format == 'format_3') {
                $html = view('constructions.ledger_format_3')
                    ->with(compact('ledger_details', 'construction', 'location', 'is_admin', 'for_pdf'))->render();
            } else {
                $html = view('constructions.ledger')
                    ->with(compact('ledger_details', 'construction', 'for_pdf', 'location'))->render();
            }

            $mpdf = $this->getMpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output($output_file_name, 'I');
        }

        if ($format == 'format_2') {
            return view('constructions.ledger_format_2')
                ->with(compact('ledger_details', 'contact', 'location'));
        } elseif ($format == 'format_3') {
            return view('constructions.ledger_format_3')
                ->with(compact('ledger_details', 'contact', 'location', 'is_admin'));
        } else {
            return view('constructions.ledger')
                ->with(compact('ledger_details', 'contact', 'location', 'is_admin'));
        }
    }
    public function show($id)
    {
        $constructionUtil = new \App\Utils\ConstructionUtil();
        $moduleUtil = new \App\Utils\ModuleUtil();
        if (!auth()->user()->can('supplier.view') && !auth()->user()->can('customer.view') && !auth()->user()->can('customer.view_own') && !auth()->user()->can('supplier.view_own')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $construction = $constructionUtil->getConstructionInfo($business_id, $id);


        $is_selected_construction = User::isSelectedContacts(auth()->user()->id);
        $user_contacts = [];
        if ($is_selected_construction) {
            $user_contacts = auth()->user()->contactAccess->pluck('id')->toArray();
        }

        if (!auth()->user()->can('supplier.view') && auth()->user()->can('supplier.view_own')) {
            if ($construction->created_by != auth()->user()->id & !in_array($construction->id, $user_contacts)) {
                abort(403, 'Unauthorized action.');
            }
        }
        if (!auth()->user()->can('customer.view') && auth()->user()->can('customer.view_own')) {
            if ($construction->created_by != auth()->user()->id & !in_array($construction->id, $user_contacts)) {
                abort(403, 'Unauthorized action.');
            }
        }

        $construction_dropdown = Construction::forDropdown();

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        //get construction view type : ledger, notes etc.
        $view_type = request()->get('view');
        if (is_null($view_type)) {
            $view_type = 'ledger';
        }

        $construction_view_tabs = $moduleUtil->getModuleData('get_contact_view_tabs');

        // $activities = Activity::forSubject($construction)
        //     ->with(['causer', 'subject'])
        //     ->latest()
        //     ->get();

        return view('constructions.show')
            ->with(compact('construction', 'construction_dropdown', 'business_id', 'view_type', 'construction_view_tabs', 'activities'));
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
    }

    public function destroy($id)
    {
        $construction = Construction::findOrFail($id);
        $construction->delete();

        return response()->json(['success' => true]);
    }

    public function checkPaymentBeforeDelete(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $payment_list = $request->input('transaction_id');

        $query = TransactionPayment::where('transaction_id', $transaction_id);

        return [
            'is_payment_exists' => !empty($query),
            'msg' => __('lang_v1.mobile_already_registered', ['contacts' => implode(', ', $contacts), 'mobile' => $mobile_number]),
        ];
    }
}
