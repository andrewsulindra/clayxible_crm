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
              @if (!empty($role->id))
              {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
              @else
              {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
              @endif
                <div class="card-body">
                  <div class="form-group">
                    <label>Role Name</label>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                  </div>
                  <div class="form-group">
                    <label>Permission</label>
                    <table class="table table-bordered">
                    <thead>                  
                      <tr>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($module as $mo)
                      <tr>
                        <td>{{ $mo->module_name }}</td>
                        <td>
                        @foreach($permission as $value)
                          @if($mo->module_name == $value->module_name)
                            @if (!empty($role->id))
                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                            @else
                            {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                            @endif
                            {{ $value->name }}
                          <br/>
                          @endif
                        @endforeach
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    </table> 
                  </div>                                   
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              {!! Form::close() !!}
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
@stop
