<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use App\Models\Donation;

class DonationController extends Controller
{
    public function donation(Request $req)
    {
        if($req->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $donation = Donation::from('donation');
            if($req->status){
                $donation->where('status', $req->status);
            }
            $donation->get();

            return \DataTables::of($donation)
                ->addColumn('action', function($donation) {
                    $button = '<button type="button" data-id="'.$donation->id.'" class="edit btn btn-outline-danger btn-sm mb-1 me-1 deletedonation"><i class="fa fa-trash"></i></button>';
                    if($donation->add_to_leaderboard =='yes')
                        $button .= ' <button type="button" data-id="'.$donation->id.'" data-status="'.$donation->add_to_leaderboard.'" class="btn btn-outline-danger btn-sm mb-1 me-1 change-leaderboard-status"><i class="fa fa-ban"></i></button>';
                    else
                        $button .= ' <button type="button" data-id="'.$donation->id.'" data-status="'.$donation->add_to_leaderboard.'" class="btn btn-outline-success btn-sm mb-1 me-1 change-leaderboard-status"><i class="fa fa-check"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if(auth()->user()->role !== 'admin')
            return redirect()->route('auth.dashboard')->with(['error' => 'Oops! You are not authorized to access this page.']);

        return view('admin.auth.donation');

    }

    public function leaderBoardStatus(Request $req)
    {
        if(auth()->user()->role !== 'admin')
            return response()->json(['error' => 'You\'re not authorized.'], 401);

        $req->validate([
            'id' => 'required|exists:donation,id',
            'status' => 'required|in:yes,no',
        ]);

        $donation = Donation::where('id',$req->id)->first();
        $donation->add_to_leaderboard = $req->status == "yes" ? "no" : "yes";
        $donation->save();
        if($donation->save())
            return response()->json(['success' => 'LeaderBoard status changed successfully'], 200);

        return response()->json(['error' => 'Something went wrong'], 500);
    }

    public function delete(Request $req)
    {
        if(auth()->user()->role !== 'admin')
            return response()->json(['error' => 'You\'re not authorized.'], 401);

        $req->validate([
            'id' => 'required|exists:donation,id',
        ]);

        $donation = Donation::where('id',$req->id)->first();

        if($donation->delete())
            return response()->json(['success' => 'Donation deleted successfully'], 200);

        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
