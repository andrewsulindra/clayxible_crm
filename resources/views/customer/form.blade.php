@extends('layouts.main')

@section('css')

@stop

@section('content')
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
            @if (session('error'))
		            <div class="alert alert-danger">
		                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                {{ session('error') }}
		            </div>
		        @endif
		        @if (session('success'))
		            <div class="alert alert-success">
		                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                {{ session('success') }}
		            </div>
		        @endif                
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="{{ url('customer' . (!empty($models->id) ? '/' . $models->id : '')) }}" enctype="multipart/form-data">
              @csrf
              @if (!empty($models->id))
				@method('put')
			  @endif
                <div class="card-body">
                  <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter firstname" value="{{ $models->firstname ?? old('firstname') }}" required autocomplete="firstname">
                  </div>
                  <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter lastname" value="{{ $models->lastname ?? old('lastname') }}" required autocomplete="lastname">
                  </div>
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" id="address1" name="address1" placeholder="Enter address" value="{{ $models->address1 ?? old('address1') }}" required autocomplete="address1">
                  </div>     
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="{{ $models->phone ?? old('phone') }}" required autocomplete="phone">
                  </div>                                   
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
@stop
