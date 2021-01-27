<?php

namespace App\Http\Controllers\Reports;
use App\Product;
use App\Support;
use App\InsDepSup;
use App\Requisition;
use App\Charts\Reports;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DepartmentInstituteSupportProduct;

class ReportsController extends Controller
{
    public function index(){
        
        // $chart = new Reports;
        // $requisitions = Requisition::where('departments_institutes_id','=', session('department_institute_id'))->get();
        // $sFs = $requisitions->where('status_id','=',6);
        // foreach($sFs as $sF){
        //     $support = Support::find($sF->supports_id);
        //     $requestSupportProduct = RequestSupportProduct::where('requests_id','=',$sF->id)->get();

        //     $sF->support = $support->name;
        // }

        // $data = array(
        //     'sF' => $sF,
        // );        
        $insDepSup = InsDepSup::where('departmentsInstitutes_id','=',session('department_institute_id'))->get();

        $supports =array();
        foreach($insDepSup as $element){
            $support = Support::find($element->supports_id);
            array_push($supports, $support);
        }
        // $prueba = $chart->chart_reports_example($data);

        $data = array(
            'supports' => $supports,
            // 'chart' => $prueba
        );
        return view('reports.reports', $data);
        // return view('reports.reports', ['chart' => $prueba]);
    }
    public function reports(Request $request){
        switch($request->action){
            case 'productsFilter':
                $iDSP = DepartmentInstituteSupportProduct::where('supports_id','=',$request->support_id)->get();
                $data = [];
                $count = 0;
                foreach($iDSP as $value){
                    $product = Product::find($value->products_id);
                    $data[$count] = $product;
                    $count++;
                }
                return $data;
            break;
        }            
        return array();
    }
}
