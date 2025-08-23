@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Skill</a></li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/skill');
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Skills</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{route('admin.skill.create')}}" class="btn btn-sm btn-outline-secondary">Add Skill </a>
            </div>
        </div>
        <div class="form-group-row">
            <div class="col-sm-12" style="text-align:center;">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message')}}
                    </div>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm" id="listall">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Skill Name</th>
                        <th>Skill Rate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($skills->count() > 0)
                        <?php        $count = 1; ?>
                        @foreach($skills as $skill)
                            <?php 

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$skill->skill_name}}</td>
                                <td>{{@$skill->skill_rate}} </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{route('admin.skill.edit', \Crypt::encrypt($skill->id)) }}"
                                        title="Edit">Edit</a> |
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$skill->id}}')">Delete</a>
                                    <form id="delete-{{$skill->id}}" action="{{ route('admin.skill.destroy', $skill->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="" style="color:red;text-align:center;">No Skill Found.....</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{-- $departments->links() --}}
            </div>
        </div>
    @endsection
@endif
@section('scripts')
    <script>
        function deleteRecord(id) {
            // alert(id)
            let choice = confirm("Are you sure? You want to delete the record Pamanently?");
            if (choice) {
                document.getElementById('delete-' + id).submit();
            }
        }
        $(document).ready(function () {
            $('#listall').DataTable();
        });


    </script>
@endsection