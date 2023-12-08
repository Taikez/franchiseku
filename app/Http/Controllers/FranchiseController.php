<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\FranchiseCategory;
use App\Models\FranchiseRating;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\User;


class FranchiseController extends Controller
{
    public function AllFranchise(){
       $allFranchise = Franchise::latest()->get();

        return view("admin.franchise.all_franchise", compact('allFranchise'));
    } // end method

    public function AllFranchiseRequest(){
        $allFranchise = Franchise::latest()->where('status','Request')->get();

        return view("admin.franchise.all_franchise_request", compact('allFranchise'));
    }


    public function RegisterFranchise(){
        $user = Auth::user();
        $allFranchiseCategory = FranchiseCategory::orderBy('franchiseCategory','asc')->get();
        return view("franchisor.add_franchise", compact('user','allFranchiseCategory'));
    }

    public function StoreFranchise(Request $req){
        // dd($req->all());

        // Validate the form data
        $validatedData = $req->validate([
            'franchiseName' => 'required|string|max:255',
            'franchiseLocation' => 'required|string|max:255',
            'franchiseCategory' => 'required|string|max:20',
            'franchisePrice' => 'required|integer',
            'franchiseReport' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip',
            'franchiseLogo' => 'required|image|mimes:jpeg,jpg,png',
        ], [
            'franchiseName.required' => 'Franchise name is required.',
            'franchiseName.string' => 'Franchise name must be a string.',
            'franchiseName.max' => 'Franchise name should not exceed 255 characters.',
            
            'franchiseLocation.required' => 'Franchise location is required.',
            'franchiseLocation.string' => 'Franchise location must be a string.',
            'franchiseLocation.max' => 'Franchise location should not exceed 255 characters.',
            
            'franchiseCategory.required' => 'Franchise category is required.',
            
            'franchisePrice.required' => 'Franchise price is required.',
            'franchisePrice.integer' => 'Franchise price must be an integer.',
            
            'franchiseReport.required' => 'Franchise report is required.',
            'franchiseReport.file' => 'Franchise report must be a file.',
            'franchiseReport.mimes' => 'Franchise report must be in PDF, Word, Excel, or ZIP format.',
            
            'franchiseLogo.required' => 'Franchise logo is required.',
            'franchiseLogo.image' => 'Franchise logo must be an image.',
            'franchiseLogo.mimes' => 'Franchise logo must be in JPEG, JPG, or PNG format.',
       
        ]);

        // dd($validatedData);

        $franchiseReport = $req->file('franchiseReport');
        $name_gen_report = hexdec(uniqid()). '.' . $franchiseReport->getClientOriginalExtension();
        $directoryReport = 'upload/FranchiseReport/';
        $saveReportUrl = $directoryReport . $name_gen_report; 

        $franchiseLogo = $req->file('franchiseLogo');
        $name_gen_logo = hexdec(uniqid()). '.' . $franchiseLogo->getClientOriginalExtension();
        $directory = 'upload/FranchiseLogo/';
        $saveLogoUrl = $directory . $name_gen_logo; 


        //get user
        $userId = Auth::id();
        $username = Auth::user()->name;

         // Create the directory if it doesn't exist
        if (!File::isDirectory($directoryReport)) {
            File::makeDirectory($directoryReport);
        }

          // Create the directory if it doesn't exist
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory);
        }

        //save to directory
        $franchiseReport->move($directoryReport, $name_gen_report);

        //store image
        Image::make($franchiseLogo)->resize(800,450)->save(public_path($directory . $name_gen_logo));

        //get franchise category name
        $franchiseCategory = FranchiseCategory::findOrFail($validatedData['franchiseCategory'])->franchiseCategory;

        Franchise::insert([
            'franchiseName' => $validatedData['franchiseName'],
            'franchiseLocation' => $validatedData['franchiseLocation'],
            'franchiseCategory' => $franchiseCategory,
            'franchisePrice' => $validatedData['franchisePrice'], 
            'franchiseReport' => $saveReportUrl,
            'franchisePIC' => $userId,
            'franchisePICName' => $username,
            'franchiseLogo' => $saveLogoUrl,
            'franchise_category_id' => $validatedData['franchiseCategory'],
            'status' => 'Request',
            'created_at' => Carbon::now(),
        ]);
        
        // $notification = array(
        //     'message' => 'Franchise Submitted! Please wait for approval',
        //     'alert-type' => 'success',
        // ); 

        // return redirect()->route('dashboard')->with($notification);


        $response = [
            'message' => 'Franchise registered successfully, please wait for approval',
            'modal' => '#successModal', // Modal ID to trigger
            ];

        // Flash the data to the session
        session()->flash('success_data', $response);        
    
        return  redirect()->route('dashboard')->with('successData', $response);

    }

    public function ApproveFranchise($id){
        $franchise = Franchise::findOrFail($id);

        $franchise->status = 'Approved';
        $franchise->save();

        $notification = array(
            'message' => $franchise->franchiseName.' Approved!',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);
    }

    public function MyFranchise(){
        //get user
        $userId = Auth::id();

        $allFranchise = Franchise::where('franchisePIC',$userId)->orderBy('created_at','desc')->get();
        $franchiseCategories = FranchiseCategory::all();

        return view('franchise.franchise', compact('allFranchise','franchiseCategories'));
    }
    

    public function RejectFranchise($id){
        $franchise = Franchise::findOrFail($id);

        $franchise->status = 'Rejected';
        $franchise->save();

        $notification = array(
            'message' => $franchise->franchiseName.' Rejected!',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);
    }
   

    public function Franchise(){
        $allFranchise = Franchise::where('status','approved')->get();
        $franchiseCategories = FranchiseCategory::all();

        return view('franchise.franchise', compact('allFranchise','franchiseCategories'));
    }

    public function FranchiseByCategory($categoryId){
        $franchise = Franchise::where('franchiseCategoryId', $categoryId)->latest()->limit(4)->get();
        $categories = franchiseCategory::all();
        $latestFranchise = Franchise::latest()->limit(4)->get();

        return view('franchise', compact('categories','latestFranchise','franchise'));
    }

    public function detail($id)
    {
        // GET EDUCATION CONTENT
        $franchise = Franchise::findOrFail($id);
        $franchisor = User::where('id', $franchise->franchisePIC)->first();
        $otherFranchise = Franchise::where('franchise_category_id', $franchise->franchise_category_id)->whereNot('id', $id)->limit(3)->get();

        // GET RATINGS 
        $ratings = franchiseRating::where(['franchiseId' => $id, 'rating' => 5])->limit(5)->get();

        return view('franchise.franchiseDetail', compact('franchise', 'otherFranchise', 'ratings','franchisor'));
    }
}
