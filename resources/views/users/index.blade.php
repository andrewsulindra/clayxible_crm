@extends('layouts.main')

@section('css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@stop

@section('content')
<section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <a href="{{ url('/users/create') }}" class="btn btn-primary">Add New</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <select id="ctrl-show-selected">
                <option value="all">Show all</option>
                <option value="selected" selected>Show Active</option>
                <option value="not-selected">Show No Active</option>
              </select>
            </div>  
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="20px">No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th hidden="true">isActive</th>
                  <th>Status</th>
                  <th>Role</th>
                  <th width="300px" data-orderable="false"></th>
                </tr>
                </thead>
                <tbody>
      
                @foreach ($models as $data)
                <tr>
                  <td>{{ $inc++ }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->email }}</td>
                  <td hidden="true">{{ $data->is_active }}</td>
                  <td>
                    @if(!empty($data->is_active == '1'))
                      <i style="color:green" class="far fa-check-circle"></i>
                    @elseif(!empty($data->is_active == '0'))
                      <i style="color:red" class="far fa-times-circle"></i>
                    @endif                  
                  </td>
                  <td>
                  @if(!empty($data->getRoleNames()))
                    @foreach($data->getRoleNames() as $v)
                      <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                  @endif
                  </td>
                  <td class="project-actions text-right">
                    @if(!empty($data->is_active == '1'))
                    <a class="btn btn-info btn-sm" href="{{ url('users/' . $data->id) }}">
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                    </a>
                    <a class="btn btn-warning btn-sm" href="{{ url('resetpassword/' . $data->id) }}">
                      <i class="fas fa-cog"></i>
                        Reset Password
                    </a>
                    <a class="btn btn-danger btn-sm" href="{{ url('users/' . $data->id) . '/deactivate'}}" onclick="return confirm('Are you sure want to deactivate this user?')">
                      <i class="far fa-times-circle"></i>
                        Deactivate
                    </a>
                    @elseif(!empty($data->is_active == '0'))
                    <a class="btn btn-secondary btn-sm" href="{{ url('users/' . $data->id) . '/reactivate'}}" onclick="return confirm('Are you sure want to reactivate this user?')">    
                      <i class="fas fa-redo"></i>                          
                        Reactivate
                    </a>
                    @endif
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@stop
@section('js')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- page script -->
<script>
$(document).ready(function (){
  var table = $('#example1').DataTable({
      "responsive": true,
      "autoWidth": false,
      "deferRender": true
  });

  $.fn.dataTable.ext.search.pop();
  $.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex){             
        return (data[3] == '1') ? true : false;
    }
  );
  
  table.draw();

   // Handle change event for "Show selected records" control
   $('#ctrl-show-selected').on('change', function(){
      var val = $(this).val();

      // If all records should be displayed
      if(val === 'all'){
         $.fn.dataTable.ext.search.pop();
         table.draw();
      }
      
      // If selected records should be displayed
      if(val === 'selected'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){             
               return (data[3] == '1') ? true : false;
            }
         );
          
         table.draw();
      }

      // If selected records should not be displayed
      if(val === 'not-selected'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){             
               return (data[3] == '1') ? false : true;
            }
         );
          
         table.draw();
      }
   });
});
</script>
@stop