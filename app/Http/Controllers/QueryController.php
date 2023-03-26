<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;

class QueryController extends Controller
{
    public function queries()
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);
            $queries = Contact::get();
            return \DataTables::of($queries)
                ->addColumn('action', function($query) {
                    $button = '<button type="button" data-id="'.$query->id.'" class="edit btn btn-outline-danger btn-sm mb-1 me-1 deletequery"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if(auth()->user()->role !== 'admin')
            return redirect()->route('auth.dashboard')->with(['error' => 'Oops! You are not authorized to access this page.']);

        return view('admin.auth.queries');
    }
    public function delete(Request $req)
    {
        if(auth()->user()->role !== 'admin')
            return response()->json(['error' => 'You\'re not authorized.'], 401);

        $req->validate([
            'id' => 'required|exists:Contacts,id',
        ]);


        $query = Contact::where('id',$req->id)->first();
        if($query->delete())
            return response()->json(['success' => 'Query deleted successfully'], 200);

        return response()->json(['error' => 'Something went wrong'], 500);
    }

}
