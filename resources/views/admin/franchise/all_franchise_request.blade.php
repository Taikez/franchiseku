@extends('admin.admin_master') 
{{-- get header, sidebar, footer --}}

@section('admin')


<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">All Franchise</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">All Franchise</h4> <br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Report</th>
                                
                                <th>Action</th>
                            </tr>
                            </thead>    

                            <tbody>
                                @php($i = 1)
                                @foreach ($allFranchise  as $item)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$item->franchiseCategory}}</td>
                                    <td style="width: 10rem">{{$item->franchiseName}}</td>
                                    <td>{{$item->franchiseLocation}}</td>
                                    <td>{{$item->franchisePrice}}</td>
                                    <td>{{$item->status}}</td>
                                    <td><a href="{{asset($item->franchiseReport)}}" download="{{$item->franchiseName}}-{{$item->franchiseReport}}">Download</a></td>
                                    <td>
                                        <a href="{{route('approve.franchise',$item->id)}}" class="btn btn-info btn-sm" title="Edit News" >
                                            Approve
                                        </a>
                                        <a href="{{route('reject.franchise',$item->id)}}" class="btn btn-danger btn-sm" title="Delete News" id="delete">
                                            Reject
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
</div>



@endsection