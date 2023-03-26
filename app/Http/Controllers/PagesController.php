<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\Media;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function albums()
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);
                $albumes = Albums::get();
                return \DataTables::of($albumes)
                ->addColumn('action', function($albumes) {
                    $button = '<button type="button" data-id="'.$albumes->id.'" class="edit btn btn-outline-danger btn-sm mb-1 me-1 deletealbum"><i class="fa fa-trash"></i></button>';
                    $button .=  '<button type="button" data-id="'.$albumes->id.'" class="edit btn btn-outline-success btn-sm mb-1 me-1 edit-album-btn"
                    data-bs-toggle="modal" data-bs-target="#update-album-modal-id" data-name="'.$albumes->name.'" data-description="'.$albumes->description.'" data-status="'.$albumes->status.'"><i class="fa fa-edit"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if(auth()->user()->role !== 'admin')
            return redirect()->route('auth.dashboard')->with(['error' => 'Oops! You are not authorized to access this page.']);

        return view('admin.auth.albums');
    }

    public function media(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $media = media::select('name','id')->where('album_id',$request->id)->get()->toArray();

            if(!empty($media))
                return response()->json(['success' => true, 'data' => $media], 200);

            return response()->json(['error' => 'Something went wrong'], 500);
        }

    }

    public function deleteAlbums(Request $req)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $req->validate([
                'id' => 'required|exists:albums,id',
            ]);

            $medias = Media::where("album_id", $req->id)->get();

            \DB::beginTransaction();
            try{
                Albums::where('id',$req->id)->delete();
                Media::where('album_id',$req->id)->delete();

                foreach($medias as $media){
                    File::delete(public_path('images/albums/').$media->name);
                }

                \DB::commit();
                return response()->json(['success' => 'Album deleted successfully'], 200);
            }catch(\Exception $e){
                \DB::rollback();
                return response()->json(['error' => 'Unable to delete album. ' . '[' . $e->getMessage() . ']']);
            }

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function deleteMedia(Request $req)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $req->validate([
                'id' => 'required|exists:medias,id',
            ]);

            $media = Media::where("id", $req->id)->first();
            \DB::beginTransaction();
            try{
                Media::where('id',$req->id)->delete();

                File::delete(public_path('images/albums/').$media->name);

                \DB::commit();
                return response()->json(['success' => 'Album deleted successfully'], 200);
            }catch(\Exception $e){
                \DB::rollback();
                return response()->json(['error' => 'Unable to delete album. ' . '[' . $e->getMessage() . ']']);
            }
        }
    }

    public function addAlbums(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            //validate all request keys
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'images' => 'required|array',
                'status' => 'nullable',
            ]);

            //transaction start
            \DB::beginTransaction();
            $saved_images = [];

            try {
                $album = new Albums();
                $album->name = $request->name;
                $album->description = $request->description;
                $album->status = $request->status ? $request->status : 0;
                $album->save();

                if($request->has('images')){
                    //save all images one by one
                    foreach($request->images as $image){
                        $filename = \Str::random(10) . time().'.'. $image->extension();
                        $image->move(public_path('images/albums'), $filename);
                        $media = new Media();
                        $media->name = $filename;
                        $media->album_id = $album->id;
                        $media->save();

                        //store file names in array to delete if error.
                        $saved_images[] = $filename;
                    }
                }

                //commit all queries in transaction.
                \DB::commit();

                // send success response
                return response()->json(['success' => 'Album Added successfully'], 200);
            } catch (\Throwable $e) {
                //rollback query
                \DB::rollback();

                //delete all images if saved any
                if($saved_images){
                    foreach($saved_images as $image){
                        File::delete("images/albums/".$image);
                    }
                }

                // send error response
                return response()->json(['error' => 'Something went wrong [' . $e->getMessage() . ']'], 500);
            }
        }

    }

    public function updateAlbums(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $request->validate([
                'id'    =>'required|integer',
                'name' => 'required',
                'description' => 'required',
                'status' => 'required',
                'images' => 'nullable|array',
            ]);
            $albums = Albums::find($request->id);
            if (!$albums) {
                return response()->json(['error' => 'Album not found'], 404);
            }

            \DB::beginTransaction();
            $file_saved = false;
            try {
                if($request->has('images')){
                    //save all images one by one
                    foreach($request->images as $image){
                        $media = new Media();
                        $filename = \Str::random(10) . time().'.'. $image->extension();
                        $image->move(public_path('images/albums'), $filename);
                        $media->name = $filename;
                        $media->album_id = $request->id;
                        $media->save();

                        //store file names in array to delete if error.
                        $saved_images[] = $filename;
                    }
                }

                $albums->name = $request->name;
                $albums->description = $request->description;
                $albums->status = $request->status;
                $albums->save();

                \DB::commit();
                return response()->json(['success' => 'Album Updated successfully'], 200);
            } catch (\Exception $e) {
                \DB::rollback();

                //delete all images if saved any
                if($saved_images){
                    foreach($saved_images as $image){
                        File::delete("images/albums/".$image);
                    }
                }

                return response()->json(['error' => 'Something went wrong [' . $e->getMessage() . ']'], 500);
            }
        }
    }

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
