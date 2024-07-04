@extends('layouts.main')

@section('css')
<style>
    #progress {
      height: 225px;
    }
  </style>
@stop

@section('content')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Projects Detail</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          @if (Auth::user()->hasAnyRole(['Super Admin', 'Manager']))
          <div class="row">
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <div class="btn-group">
                <button type="button" class="btn btn-default">{{ $project_status }}</button>
                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/1') }}">Open</a>
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/2') }}">Confirm</a>
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/3') }}">Follow Up</a>
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/4') }}">Need Follow Up</a>
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/5') }}">Close Paid</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ url('project/' . $projects->id . '/change_status/6') }}">Close Unpaid</a>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="row">
            <h6 class="col-12 col-md-12 col-lg-4 order-1 order-md-2">Status: {{ $project_status }}</h6>
          </div>
          @endif
          <br><br>
          <div class="row">
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <h3 class="text-primary"></i> {{ $projects->name }}</h3>
              <p class="text-muted">
                Category: {{ $project_category->name }}
              </p>
              <p class="text-muted">
                {{ $projects->address1 }}
                <br>
                {{ $cities->city_name }}
                <br>
                {{ $cities->state_name }}, {{ $cities->country_name }}
              </p>
              <br>
              <div class="text-muted">
                <p class="text-sm">
                    Owner Detail
                    <br>
                    <b>{{ $owners->name }}</b>
                    <br>
                    Category: {{ $owner_category->name }}
                    <br>
                    {{ $owners->address1 }}
                    <br>
                    {{ $cities_owner->city_name }}
                    <br>
                    {{ $cities_owner->state_name }}, {{ $cities_owner->country_name }}

                </p>
              </div>

              <h5 class="mt-5 text-muted">Project Detail</h5>
              <p class="text-muted">
                {!! nl2br(e($projects->notes)) !!}
              </p>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->





      <!-- Default box -->
      <div class="card collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Submit Progress</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <form role="form" method="post" action="{{ url('project/' . $projects->id . '/submit_progress') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <textarea class="form-control" id="progress" name="progress" required autocomplete="progress"></textarea>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->











      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Projects Activity</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 order-2 order-md-1">
              <div class="row">
                <div class="col-12">
                  @foreach ($project_log as $data_log)
                  <div class="post">
                    <div class="user-block">
                      <img class="img-circle img-bordered-sm" src="{{ $data_log->created_by_image }}" alt="user image">
                      <span class="username">
                        <a href="#">{{ $data_log->created_by_name }}</a>
                      </span>
                      <span class="description">{{ $data_log->created_at->format('d F Y - H:i:s') }}</span>
                    </div>
                    @if ($data_log->type == config('constants.PROJECT_LOG_TYPE_ADD_PROGRESS'))
                      <p>
                        {!! nl2br(e($data_log->new)) !!}
                      </p>
                    @elseif ($data_log->type == config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'))
                      Change Status
                      <br>
                      {{ $data_log->project_status_name_old }} => {{ $data_log->project_status_name_new }}
                    @elseif ($data_log->type == config('constants.PROJECT_LOG_TYPE_CHANGE_SALES'))
                      Change Sales
                      <br>
                      {{ $data_log->project_sales_name_old }} => {{ $data_log->project_sales_name_new }}
                    @elseif ($data_log->type == config('constants.PROJECT_LOG_TYPE_CREATE'))
                      {{ $data_log->new }}
                    @endif
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->




    </section>
@stop
@section('js')

@stop