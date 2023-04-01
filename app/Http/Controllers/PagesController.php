<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PagesController extends Controller
{

    public function members()
    {
        if(auth()->user()->role !== 'admin')
            return redirect()->route('auth.dashboard')->with(['error' => 'Oops! You are not authorized to access this page.']);

        $members = Members::all();

        return view('admin.auth.members', compact("members"));
    }

    public function addMembers(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $request->validate([
                'name' => 'required',
                'designation' => 'required',
                'quote' => 'required',
                'image' => 'required',
            ]);
            $member = new Members();

            if ($request->hasFile('image')) {
                $filename = time().'.'. $request->image->extension();
                if (!request()->image->move(public_path('images/members'), $filename)) {
                    return response()->json(['error' => 'Unable to save image.'], 500);
                }
                $member->image = $filename;
            }

            $member->name = $request->name;
            $member->designation = $request->designation;
            $member->quote = $request->quote;

            if($member->save()){
                return response()->json(['success' => 'Member added successfully!'], 200);
            }else{
                return response()->json(['error' => 'Oops! Something went wronge.'], 500);
            }
        }
    }

    public function deleteMembers(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $request->validate([
                'id' => 'required|exists:members,id',
            ]);

            $member = Members::where('id',$request->id)->first();
            $image = $member->image;

            if($member->delete()){
                File::delete("images/members/".$image);
                return response()->json(['success' => 'Member deleted successfully'], 200);
            }

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function updateMember(request $request)
    {
        if (auth()->user()->role !== 'admin')
            return response()->json(['error' => 'You\'re not authorized.'], 401);

        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'quote' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',

        ]);
        $member = Members::find($request->id);
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 500);
        }

        \DB::beginTransaction();
        $file_saved = false;
        try {
            if($request->image){
                $imageName = time() . '.' . $request->image->extension();
                $oldName = $member->image;
                if (!request()->image->move(public_path('images/members'), $imageName)) {
                    return response()->json(['error' => 'Unable to save image'], 500);
                } else {
                    $file_saved = true;
                    $member->image = $imageName;
                }
            }

            $member->name = $request->name;
            $member->designation = $request->designation;
            $member->quote = $request->quote;
            $member->save();

            if(!empty($imageName)) File::delete('images/members/' . $oldName);

            \DB::commit();
            return response()->json(['success' => 'Member Updated successfully'], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            if ($file_saved) File::delete('images/members/' . $imageName);
            return response()->json(['error' => 'Something went wrong [' . $e->getMessage() . ']'], 500);
        }
    }
}
