<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Members;
use App\Models\About;
use App\Models\Album;
use App\Models\Albums;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function donate()
    {
        $donors = Donation::select('donation.*', 'cities.name as city_name', 'countries.name as country_name')
            ->leftJoin('cities', 'donation.city_id', '=', 'cities.id')
            ->leftJoin('countries', 'donation.country_id', '=', 'countries.id')
            ->where('status', 'paid')
            ->where('add_to_leaderboard', 'yes')
            ->orderby('amount', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get();

        return view('donate', compact('donors'));
    }

    public function contact()
    {
        return view('contact');
    }
    public function about()
    {
        $member=Members::all();
        return view('about',compact('member'));
    }
    public function albums()
    {
        $albums = Albums::select('*')
            ->with('media')
            ->where('albums.status', 1)
            ->orderBy('id', 'DESC')
            ->paginate(12);

        return view('albums',compact('albums'));
    }

    public function album($id)
    {
        $album = Albums::where('id', $id)
            ->with('media')
            ->where('albums.status', 1)
            ->first();

        if (!$album) return redirect()->route('home.albums')->with('error', 'Album does not exists');

        return view('album', compact('album'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'message' => 'required|string',
        ]);
        try {
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->mobile = $request->mobile;
            $contact->message = $request->message;
            $contact->save();
            return redirect()->back()->with(['success' => 'Contact Form submited successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong.[' . $e->getMessage() . ']'])->withInput();
        }
    }
    public function privacy()
    {
        return view('privacy');
    }

    public function leaderboard()
    {
        $donors = Donation::select('donation.*', 'cities.name as city_name', 'countries.name as country_name')
            ->leftJoin('cities', 'donation.city_id', '=', 'cities.id')
            ->leftJoin('countries', 'donation.country_id', '=', 'countries.id')
            ->where('status', 'paid')
            ->where('add_to_leaderboard', 'yes')
            ->orderby('amount', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('leaderboard', compact('donors'));
    }

    public function findCountries(Request $req)
    {
        if ($req->ajax()) {
            $term = trim($req->term);
            $posts = Country::select('id', 'name as text')
                ->where('name', 'LIKE', $term . '%')
                ->simplePaginate(20, ['*'], 'page', $req->page);

            $morePages = true;
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function findStates(Request $req)
    {
        if ($req->ajax()) {
            $req->validate([
                'country_id' => 'required|integer',
            ]);

            $term = trim($req->term);
            $posts = State::select('id', 'name as text')
                ->where('country_id', $req->country_id)
                ->where('name', 'LIKE', $term . '%')
                ->simplePaginate(20, ['*'], 'page', $req->page);

            $morePages = true;
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function findCities(Request $req)
    {
        if ($req->ajax()) {
            $req->validate([
                'country_id' => 'required|integer',
                'state_id' => 'required|integer',
            ]);

            $term = trim($req->term);
            $posts = City::select('id', 'name as text')
                ->where('country_id', $req->country_id)
                ->where('state_id', $req->state_id)
                ->where('name', 'LIKE', $term . '%')
                ->simplePaginate(20, ['*'], 'page', $req->page);

            $morePages = true;
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

}
