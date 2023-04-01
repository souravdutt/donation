<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
{
    public function albums()
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);
                $albums = Albums::get();
                return \DataTables::of($albums)
                    ->editColumn('description', function($album){
                        return \Str::words(strip_tags($album->description), 10, '...');
                    })
                    ->addColumn('action', function($album) {
                        $button = '<button type="button" data-id="'.$album->id.'" class="edit btn btn-outline-danger btn-sm mb-1 me-1 deletealbum"><i class="fa fa-trash"></i></button>';
                        $button .=  '<button type="button" data-id="'.$album->id.'" class="edit btn btn-outline-success btn-sm mb-1 me-1 edit-album-btn"><i class="fa fa-edit"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        if(auth()->user()->role !== 'admin')
            return redirect()->route('auth.dashboard')->with(['error' => 'Oops! You are not authorized to access this page.']);

        return view('admin.auth.albums');
    }

    public function addAlbums(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            //validate all request keys
            $request->validate([
                'title' => 'required|string|min:20|max:100',
                'description' => 'required|min:50',
                'images' => 'required|array',
                'status' => 'nullable',
            ]);

            //transaction start
            DB::beginTransaction();
            $saved_images = [];

            try {
                $album = new Albums();
                $album->name = $request->title;
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
                DB::commit();

                // send success response
                return response()->json(['success' => 'Album Added successfully'], 200);
            } catch (\Throwable $e) {
                //rollback query
                DB::rollback();

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
                'title' => 'required|string|min:20|max:100',
                'description' => 'required|min:50',
                'status' => 'nullable',
                'images' => 'nullable|array',
            ]);

            $albums = Albums::find($request->id);
            if (!$albums) return response()->json(['error' => 'Album not found'], 404);

            DB::beginTransaction();
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

                $albums->name = $request->title;
                $albums->description = $request->description;
                $albums->status = $request->status ? $request->status : 0;
                $albums->save();

                DB::commit();
                return response()->json(['success' => 'Album Updated successfully'], 200);
            } catch (\Exception $e) {
                DB::rollback();

                //delete all images if saved any
                if(!empty($saved_images)){
                    foreach($saved_images as $image){
                        File::delete("images/albums/".$image);
                    }
                }

                return response()->json(['error' => 'Something went wrong [' . $e->getMessage() . ']'], 500);
            }
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

            DB::beginTransaction();
            try{
                Albums::where('id',$req->id)->delete();
                Media::where('album_id',$req->id)->delete();

                foreach($medias as $media){
                    File::delete(public_path('images/albums/').$media->name);
                }

                DB::commit();
                return response()->json(['success' => 'Album deleted successfully'], 200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['error' => 'Unable to delete album. ' . '[' . $e->getMessage() . ']']);
            }

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function albumDetail(request $request)
    {
        if(request()->ajax()) {
            if(auth()->user()->role !== 'admin')
                return response()->json(['error' => 'You\'re not authorized'], 401);

            $album = Albums::where("id",$request->id)->with('media')->first();

            if(!empty($album))
                return response()->json(['success' => true, 'album' => $album], 200);

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
            DB::beginTransaction();
            try{
                Media::where('id',$req->id)->delete();

                File::delete(public_path('images/albums/').$media->name);

                DB::commit();
                return response()->json(['success' => 'Album deleted successfully'], 200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['error' => 'Unable to delete album. ' . '[' . $e->getMessage() . ']']);
            }
        }
    }

    public function uploadCKEditorMedia($type, Request $request)
    {

            $request->validate([
                "upload" => "required|mimes:png,jpg,gif|max:1024",
            ]);

            if($type == "album"){
                # save image
                $filename = \Str::random(10) . time().'.'. $request->upload->extension();
                if($request->upload->move(public_path('uploads'), $filename)){
                    return response()->json([
                        "uploaded" => 1,
                        "fileName" => $filename,
                        "url" => url('uploads') . "/" . $filename,
                    ]);
                }
            }

            return response()->json(['error' => 'Something went wrong'], 500);

    }
}
