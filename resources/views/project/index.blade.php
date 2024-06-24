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
              <a href="{{ url('/project/create') }}" class="btn btn-primary">Add New</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <select id="ctrl-show-selected">
                <option value="all" selected>Show all</option>
                <option value="open">Open</option>
                <option value="confirm">Confirm</option>
                <option value="need_follow_up">Need Follow Up</option>
                <option value="close_paid">Close Paid</option>
                <option value="close_unpaid">Close Unpaid</option>
              </select>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="20px">No</th>
                  <th hidden="true">isActive</th>
                  <th hidden="true">status</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Sales</th>
                  <th>Status</th>
                  <th width="180px" data-orderable="false"></th>
                </tr>
                </thead>
                <tbody>

                @foreach ($models as $data)
                <tr>
                  <td>{{ $inc++ }}</td>
                  <td hidden="true">{{ $data->is_active }}</td>
                  <td hidden="true">{{ $data->project_status }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->address1 }}</td>
                  <td>{{ $data->sales->name }}</td>
                  <td>{{ projectStatusName($data->project_status) }}</td>
                  <td class="project-actions text-right">
                    @if(!empty($data->is_active == '1'))
                    <a class="btn btn-success btn-sm" href="{{ url('project/' . $data->id . '/detail') }}">
                        <i class="fa fa-eye"></i>
                    </a>
                      @if (Auth::user()->hasAnyPermission(['Edit project']))
                      <a class="btn btn-info btn-sm" href="{{ url('project/' . $data->id) }}">
                          <i class="fas fa-pencil-alt"></i>
                      </a>
                      @endif
                      @if (Auth::user()->hasAnyRole(['Super Admin', 'Manager']))
                      <a class="btn btn-danger btn-sm" href="{{ url('project/' . $data->id) . '/deactivate'}}" onclick="return confirm('Are you sure want to deactivate this data?')">
                        <i class="far fa-times-circle"></i>
                      </a>
                      @endif
                    @elseif(!empty($data->is_active == '0'))
                      @if (Auth::user()->hasAnyRole(['Super Admin', 'Manager']))
                      <a class="btn btn-secondary btn-sm" href="{{ url('project/' . $data->id) . '/reactivate'}}" onclick="return confirm('Are you sure want to reactivate this data?')">
                        <i class="fas fa-redo"></i>
                      </a>
                      @endif
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
        return (data[1] == '1') ? true : false;
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
      if(val === 'open'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '1') ? true : false;
            }
         );

         table.draw();
      }
      // If selected records should be displayed
      if(val === 'confirm'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '2') ? true : false;
            }
         );

         table.draw();
      }
      // If selected records should be displayed
      if(val === 'confirm'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '2') ? true : false;
            }
         );

         table.draw();
      }
      // If selected records should be displayed
      if(val === 'need_follow_up'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '3') ? true : false;
            }
         );

         table.draw();
      }
      // If selected records should be displayed
      if(val === 'close_paid'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '4') ? true : false;
            }
         );

         table.draw();
      }
      // If selected records should be displayed
      if(val === 'close_unpaid'){
         $.fn.dataTable.ext.search.pop();
         $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex){
               return (data[2] == '5') ? true : false;
            }
         );

         table.draw();
      }
   });
});
</script>
@stop