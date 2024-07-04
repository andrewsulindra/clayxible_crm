@extends('layouts.main')

@section('css')
  <!-- Select2 -->
  <!-- <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}"> -->
  <!-- <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}"> -->
  <style>
    .select2-results__option {
      white-space: normal;
    }
    #notes {
      height: 225px;
    }
  </style>
@stop

@section('content')
<section class="content">
      <div class="container-fluid">

      <form role="form" method="post" action="{{ url('project' . (!empty($models->id) ? '/' . $models->id : '')) }}" enctype="multipart/form-data">
        @csrf
        @if (!empty($models->id))
          @method('put')
        @endif

        <div class="row">
        <!-- form start -->


        <!-- @if (session('error'))
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
        @endif -->
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- /.card-header -->
                <div class="card-body">

                  <div class="form-group">
                    <label>Project Category</label>
                    <select class="form-control" style="width: 100%;" id="project_category_id" name="project_category_id" required>
                      @if (!empty($project_category))
                      @foreach ($project_category as $pc)
                        <option value="{{ $pc->id }}" {{ isset($models) && $models->project_category_id == $pc->id ? 'selected' : '' }}>{{ $pc->name }}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>



                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $models->name ?? old('name') }}" required autocomplete="name">
                  </div>
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" id="address1" name="address1" placeholder="Enter address" value="{{ $models->address1 ?? old('address1') }}" required autocomplete="address1">
                  </div>
                  <div class="form-group">
                    <!-- <label>City</label> -->
                    <!-- <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="{{ $models->city ?? old('city') }}" required autocomplete="city"> -->


                    <label>City</label>
                    <select class="form-control select2 select2-city" style="width: 100%;" id="city" name="city" required>
                      <option value=""></option>
                      @if (!empty($cities))
                      @foreach ($cities as $city)
                        <option value="{{ $city->city_id }}" {{ isset($models) && $models->city == $city->city_id ? 'selected' : '' }}>{{ $city->city_name }}|{{ $city->state_name }}</option>
                      @endforeach
                      @endif
                    </select>



                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="{{ $models->phone ?? old('phone') }}" required autocomplete="phone">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ $models->email ?? old('email') }}" required autocomplete="email">
                  </div>
                  <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="notes" name="notes" required autocomplete="notes">{{ $models->notes ?? old('notes') }}</textarea>
                  </div>

                  <!-- <div class="form-group">
                    <label>Minimal</label>
                    <select class="form-control select2" style="width: 100%;">
                      <option selected="selected"></option>
                      <option>Alabama</option>
                      <option>Alaska</option>
                      <option>California</option>
                      <option>Delaware</option>
                      <option>Tennessee</option>
                      <option>Texas</option>
                      <option>Washington</option>
                    </select>
                  </div> -->


                  <!-- <div class="form-group">
                      <label for="city">City</label>
                      <select class="form-control" id="city" name="city" required>
                          <option value="">Loading cities...</option>
                      </select>
                  </div> -->

                </div>
                <!-- /.card-body -->


            </div>
            <!-- /.card -->



          </div>
          <!--/.col (left) -->





          <!-- left column -->
          <div class="col-md-6">



          @if (empty($models->id))
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">New Owner</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Owner List</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                      <div class="form-group">
                        <label>Owner Category</label>
                        <select class="form-control" style="width: 100%;" id="owner_category_id" name="owner_category_id">
                          @if (!empty($owner_category))
                          @foreach ($owner_category as $oc)
                            <option value="{{ $oc->id }}">{{ $oc->name }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Enter name" value="{{ $models->name ?? old('name') }}"  autocomplete="name">
                      </div>
                      <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="owner_address1" name="owner_address1" placeholder="Enter address" value="{{ $models->address1 ?? old('address1') }}"  autocomplete="address1">
                      </div>
                      <div class="form-group">
                        <label>City</label>
                        <select class="form-control select2 select2-city" style="width: 100%;" id="owner_city" name="owner_city">
                          <option value=""></option>
                          @if (!empty($cities))
                          @foreach ($cities as $city)
                            <option value="{{ $city->city_id }}" {{ isset($models) && $models->city == $city->city_id ? 'selected' : '' }}>{{ $city->city_name }}|{{ $city->state_name }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="owner_phone" name="owner_phone" placeholder="Enter phone" value="{{ $models->phone ?? old('phone') }}"  autocomplete="phone">
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" id="owner_email" name="owner_email" placeholder="Enter email" value="{{ $models->email ?? old('email') }}"  autocomplete="email">
                      </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                      <div class="form-group">
                        <label>Owner</label>
                        <select class="form-control select2 select2-owner" style="width: 100%;" id="owner_id" name="owner_id">
                          <option value="" selected="selected"></option>
                          @if (!empty($owners))
                          @foreach ($owners as $owner)
                            <option value="{{ $owner->id }}">{{ $owner->name }}|{{ $owner->address1 }}|{{ $owner->phone }}|{{ $owner->email }}|{{ $owner->city_name }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>

                      <br>
                      <div class="form-group">
                        <div class="text-muted detail_owner" style='display:none'>
                          <p class="text-sm">Owner Name
                            <b class="d-block" id='detail_owner_name'>-</b>
                          </p>
                          <p class="text-sm">Owner Address
                            <b class="d-block" id='detail_owner_address1'>-</b>
                          </p>
                          <p class="text-sm">Owner Phone
                            <b class="d-block" id='detail_owner_phone'>-</b>
                          </p>
                          <p class="text-sm">Owner Email
                            <b class="d-block" id='detail_owner_email'>-</b>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
            </div>
          @else
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label>Owner</label>
                  <select class="form-control select2 select2-owner" style="width: 100%;" id="owner_id" name="owner_id">
                    <option value="" selected="selected"></option>
                    @if (!empty($owners))
                    @foreach ($owners as $owner)
                      <option value="{{ $owner->id }}" {{ isset($models) && $models->owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }}|{{ $owner->address1 }}|{{ $owner->phone }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>
            @if (Auth::user()->hasAnyPermission(['Edit sales']))
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label>Sales</label>
                  <select class="form-control select2 select2-user" style="width: 100%;" id="sales_id" name="sales_id">
                    @if (!empty($users))
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}" {{ isset($models) && $models->sales_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>
            @endif
          @endif
          <!--/.col (left) -->






        </div>
        <!-- /.row -->

          <div class="col-md-6">
            <button type="submit" class="btn btn-primary">
            @if (!empty($models->id))
              Update
            @else
              Submit
            @endif
            </button>
          </div>

        </form>

      </div><!-- /.container-fluid -->
    </section>
@stop

@section('js')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2-city').select2({
        templateResult: formatStateCity,
        templateSelection: formatStateSelectionCity
      })
    $('.select2-owner').select2({
        templateResult: formatStateOwner,
        templateSelection: formatStateSelectionOwner
      })
    $('.select2-user').select2({
      })
  })
</script>
<script>
  function formatStateCity(state) {
    if (!state.id) {
      return state.text;
    }
    var baseUrl = state.text.split('|');
    var $state = $(
      '<span>' + baseUrl[0] + '<br><small>' + baseUrl[1] + '</small></span>'
    );
    return $state;
  }
  function formatStateSelectionCity(state) {
    if (!state.id) {
      return state.text;
    }
    var baseUrl = state.text.split('|');
    var $state = $(
      '<span>' + baseUrl[0] + '</span>'
    );
    return $state;
  }


  function formatStateOwner(state) {
    if (!state.id) {
      return state.text;
    }
    var baseUrl = state.text.split('|');
    var stateText = `${baseUrl[0]}<br><small>${baseUrl[1] ? `${baseUrl[1]}, ` : ''}${baseUrl[4]}</small>`;
    var $state = $('<span>').html(stateText);


    return $state;
  }
  function formatStateSelectionOwner(state) {
    if (!state.id) {
      return state.text;
    }
    var baseUrl = state.text.split('|');
    var $state = $(
      '<span>' + baseUrl[0] + '</span>'
    );

    $('#detail_owner_name').text(baseUrl[0]);
    $('#detail_owner_address1').text(baseUrl[1]);
    $('#detail_owner_phone').text(baseUrl[2]);
    $('#detail_owner_email').text(baseUrl[3]);
    $('.detail_owner').show();

    return $state;
  }
</script>
<script>
    $(document).ready(function() {
      $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href"); // Get the href of the tab

        // Reset the content of the New Owner tab
        if (target === "#custom-tabs-four-home") {
          $('#owner_name').val(''); $('#owner_address1').val(''); $('#owner_phone').val(''); $('#owner_email').val('');
        }

        // Reset the content of the Owner List tab
        if (target === "#custom-tabs-four-profile") {
          $('#owner_id').val('').trigger('change');
          $('#detail_owner_name').text('-');
          $('#detail_owner_address1').text('-');
          $('#detail_owner_phone').text('-');
          $('#detail_owner_email').text('-');
          $('.detail_owner').hide();
        }
      });
    });
  </script>
@stop