<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Session;
use App\WorkOrder;
use App\Division;
use App\Department;


class WorkOrderController extends Controller
{   
    public function index()
    {   
          $divisions = Division::all();
        $workorders =  WorkOrder::select('id','vendor_code','order_code','order_validity')->OrderBy('id','DESC')->get();
        return view('admin.orders.index',compact('workorders','divisions'));
    }

    
    public function create()
    {
       $divisions = Division::all();
        return view('admin.orders.create',compact('divisions'));
    }

    public function store(Request $request)
    {

        $workorder =  WorkOrder::create([
            'order_code'  => $request->work_order,
            'vendor_code' => $request->vendor_code,
            'order_validity' => date('Y-m-d',strtotime($request->order_validity)),
            'division_id' =>$request->division_id,
            'department_id' =>$request->department_id
        ]);
      
        if($workorder){
            return back()->with('message','Work Order Suceessfully Added');
        }
        else{
            return back()->with('message','Ooops Error While Adding Job');
        }
    }
    

    public function show(WorkOrder $work)
    {
    }


    public function edit($wIDencription)
    {
        $id = \Crypt::decrypt($wIDencription);
        $workOrder   = WorkOrder::where('id',$id)->first();
         $divisions   = Division::all();
        $departments = Department::where('division_id',@$workOrder->division_id)->get();
        return view('admin.orders.edit',compact('workOrder','id','divisions','departments'));
    }


    public function update(Request $request, WorkOrder $workOrder)
    {
        $update =  WorkOrder::where('id',$workOrder->id)->update([
            'order_code'       => $request->order_code,
            'vendor_code'      => $request->vendor_code,
            'order_validity'   => $request->order_validity,
            'division_id'      => $request->division_id,
            'department_id'    => $request->department_id
        ]);

        if($update){
            
            return back()->with('message','Work order Updated!');
        }
        else{
             return back()->with('message','OOPs... Something Wrong');
        }
    }

   
    public function destroy(WorkOrder $workOrder)
    {
        if($workOrder->id != 0)
        {
            $job_destroy  = $workOrder->delete();
            return redirect('admin/work-order')->with('message' ,'Record Successfully Deleted!!');

        }
    }


}
