@extends('admin.admin_master') 
{{-- get header, sidebar, footer --}}

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Add Franchise Category</h4>

                        <br>
                     
                        <form action="{{route('post.franchise.category')}}" method="POST">
                            @csrf


                            {{-- blog category  --}}
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">franchise Category Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="franchiseCategory" type="text" placeholder="franchise Category" id="example-text-input" >
                                   @error('franchiseCategory')
                                       <span class="text-danger">{{$message}}</span>
                                   @enderror 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea name="franchiseCategoryDesc" class="form-control"  id="franchiseCategoryDesc" cols="30" rows="10" placeholder="Put your description here.."></textarea>
                                   @error('franchiseCategoryDesc')
                                       <span class="text-danger">{{$message}}</span>
                                   @enderror 
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info waves-effect waves-light" id="submitBtn" value="Insert Franchise Category">

                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>

@endsection