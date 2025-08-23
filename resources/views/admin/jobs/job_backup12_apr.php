@if(Session::get('user_sub_typeSession') == 3)
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
        <div class="col-sm-10">
            <select class="form-control" id="division_id" name="division_id">
                <option value="null">Select Division</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}">{{$division->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
        <div class="col-sm-10">
            <select class="form-control" id="department_id" name="department_id">
                <option value="null">Select Department</option>    
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Section</label>
        <div class="col-sm-10">
            <select class="form-control" id="section_id" name="section_id">
                <option value="null">Select Section</option>
                
            </select>
        </div>
    </div>
    @else
    <?php 
        $division = Division::where('id',Session::get('user_DivID_Session'))->get();   
        $department = Department::where('id',Session::get('user_DeptID_Session'))->get();   
        $section = Section::where('department_id',Session::get('user_DeptID_Session'))->get();   
    ?>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="division_id"> 
                <option value="{{$division[0]->id}}">{{$division[0]->name}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="department_id">
                <option value="{{@$department[0]->id}}">{{@$department[0]->department_name}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Section</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="section_id">
                @if($section->count() > 0)
                    @foreach($section as $sec)
                      <option value="{{$sec->id}}">{{$sec->name}}</option>
                    @endforeach
                @endif   
            </select>
        </div>
    </div>
    @endif



<script type="text/javascript">
        // get the Department data
    $('#division_id').on('change',function(){
            var division_ID = $(this).val();
                // alert(division_ID);
            $("#department_id").html('<option value="">--Select--</option>');
            $("#section_id").html('<option value="">--Select--</option>');
            if(division_ID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.job.department')}}/" + division_ID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for(var i=0;i<data.length;i++){
                            $("#department_id").append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                        }
                    }
                });
            }else{
                $('#department_id').html('<option value="null">Select Department</option>');
            }
    });

    // get the Section data
    $('#department_id').on('change',function(){
            var department_ID = $(this).val();
                // alert(department_ID);
            $("#section_id").html('<option value="">--Select--</option>');
            if(department_ID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.job.section')}}/" + department_ID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for(var i=0;i<data.length;i++){
                            $("#section_id").append('<option value="'+data[i].id+'" >'+data[i].name+'</option>');
                        }
                    }
                });
            }else{
                $('#section_id').html('<option value="null">Select Department</option>');
            }
    });
</script>
<tbody id="append_gatepass">
                    <tr class="remove_tr" id="remove_tr">
                        <td>
                            <select class="form-control rec" id="division_id" name="division_id"  onchange="getProduct_Stock(this,this.value)">
                                <option value="">Select Division</option>
                                @if($divisions->count() > 0)
                                    @foreach($divisions as $division)
                                        <option value="{{@$division->id}}">{{@$division->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="" name="">
                                {{--<option value="null">Select Department</option>
                                @if($departments->count() > 0)
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->department_name}}</option>
                                    @endforeach
                                @endif --}}
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="" name="">
                                {{--<option value="null">Select Section</option>
                                @if($sections->count() > 0)
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}">{{$section->name}}</option>
                                    @endforeach
                                @endif--}}
                            </select>
                        </td>
                    </tr>
                </tbody>

{{--@if($departments->count() > 0)
@foreach($departments as $department)
    <option value="{{$department->id}}">{{$department->department_name}}</option>
@endforeach
@endif 

 {{--<option value="null">Select Section</option>
@if($sections->count() > 0)
    @foreach($sections as $section)
        <option value="{{$section->id}}">{{$section->name}}</option>
    @endforeach
@endif--}}


// alert(divisionID);
        // if(stockid!="")
        // {
        //     var c=0;
        //     $(".checkValue"+uniqueid).each(function(e){
        //         if($(this).val()==stockid)
        //         {
        //             c++;
        //         }
        //     })
        //     if(c==1)
        //     {
        //         if(productid && stockid)
        //         {
        //             $.ajax({
        //             type:"POST",
        //             url:"get-stock-calculation.php",
        //             data: {
        //                 'productid': productid,
        //                 'stockid': stockid
        //             },
        //             dataType:"JSON",
        //                 success:function(data){
        //                  console.log(data.available);
        //                     $(th).closest('tr').find('.showStock'+uniqueid).val(data.available);
        //                     $(th).closest('tr').find('.tranferStock'+uniqueid).val("");
        //                 }
        //             });
        //         }
        //         else{
        //            alert("Please Select Product Name.");
        //            $(th).val("");
        //            $(th).closest('tr').find('.showStock'+uniqueid).val("");
        //            $(th).closest('tr').find('.tranferStock'+uniqueid).val("");
        //         }
        //     }
        //     else
        //     {
        //         alert("This Stock Already Added.");
        //         $(th).val("");
        //         $(th).closest('tr').find('.showStock'+uniqueid).val("");
        //         $(th).closest('tr').find('.tranferStock'+uniqueid).val("");
        //     }
        // }