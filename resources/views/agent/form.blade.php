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
              <form role="form" method="post" action="{{ url('agent' . (!empty($models->id) ? '/' . $models->id : '')) }}" enctype="multipart/form-data">
              @csrf
              @if (!empty($models->id))
				@method('put')
			  @endif
                <div class="card-body">
                  <div class="form-group">
                    <label>Agent Name</label>
                    <input type="text" class="form-control" id="agent_name" name="agent_name" placeholder="Enter Agent Name" value="{{ $models->agent_name ?? old('agent_name') }}" required autocomplete="agent_name">
                  </div>
                  <div class="form-group">
                    <label>PIC Name</label>
                    <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="Enter PIC Name" value="{{ $models->pic_name ?? old('pic_name') }}" required autocomplete="pic_name">
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
