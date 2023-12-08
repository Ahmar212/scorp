<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\DealApplication;
use App\Models\ClientDeal;
use App\Models\University;
use App\Models\Stage;
use App\Models\User;

class ApplicationsController extends Controller
{
    //

    private function ApplicationFilters()
    {
        $filters = [];
        if (isset($_GET['applications']) && !empty($_GET['applications'])) {
            $filters['name'] = $_GET['applications'];
        }


        if (isset($_GET['stages']) && !empty($_GET['stages'])) {
            $filters['stage_id'] = $_GET['stages'];
        }

        if (isset($_GET['created_by']) && !empty($_GET['created_by'])) {
            $filters['created_by'] = $_GET['created_by'];
        }

        if (isset($_GET['universities']) && !empty($_GET['universities'])) {
            $filters['university_id'] = $_GET['universities'];
        }

        return $filters;
    }

    public function index(){
        $usr = \Auth::user();

         //////////////pagination calculation
         $start = 0;
         $num_results_on_page = 50;
         if (isset($_GET['page'])) {
             $page = $_GET['page'];
             $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
             $start = ($page - 1) * $num_results_on_page;
         } else {
             $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
         }
 
         /////////////////end pagination calculation

         if ($usr->can('view application') || $usr->type == 'super admin') {

            $app_query = DealApplication::select(['deal_applications.*']);

            if ($usr->type == 'super admin') { 
                $app_query->join('deals', 'deals.id', 'deal_applications.deal_id');
            }else if ($usr->type == 'company') {
                $app_query->join('deals', 'deals.id', 'deal_applications.deal_id')->where('deals.created_by', $usr->id);
            }else {
                $app_query->join('deals', 'deals.id', 'deal_applications.deal_id')->where('deals.created_by', $usr->created_by);
            }
            $total_records = $app_query->count();

            //$filters 
            $app_for_filer = $app_query->get();


            $filters = $this->ApplicationFilters();
            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $app_query->whereIn('deal_applications.name', $value);
                } elseif ($column === 'stage_id') {
                    $app_query->whereIn('deal_applications.stage_id', $value);
                } elseif ($column == 'university_id') {
                    $app_query->whereIn('deal_applications.university_id', $value);
                }elseif ($column == 'created_by') {
                    $app_query->whereIn('deals.created_by', $value);
                }
            }

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true' && isset($_GET['search']) && !empty($_GET['search'])) {
                $g_search = $_GET['search'];
                $app_query->Where('deal_applications.name', 'like', '%' . $g_search . '%');
                $app_query->orWhere('deal_applications.application_key', 'like', '%' . $g_search . '%');
                $app_query->orWhere('deal_applications.course', 'like', '%' . $g_search . '%');
            }

            $applications = $app_query->get();
            $universities = University::get()->pluck('name', 'id')->toArray();
            $stages = Stage::get()->pluck('name', 'id')->toArray();
            $brands = User::where('type', 'company')->get();

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('applications.applications_list_ajax',  compact('applications', 'total_records', 'universities', 'stages', 'app_for_filer', 'brands'))->render();

                return json_encode([
                    'status' => 'success',
                    'html' => $html
                ]);
            }


            return view('applications.index', compact('applications', 'total_records', 'universities', 'stages', 'app_for_filer', 'brands'));

         }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
         }
    }

    public function getDealApplication(){
        $id = $_GET['id'];
        $applications = \App\Models\DealApplication::where('deal_id', $id)->pluck('application_key', 'id');
        
        $html = '<option value=""> Select Application</option>';

        foreach($applications as $key => $app){
            $html .= '<option value="'.$key.'">'.$app.'</option>';
        }

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
