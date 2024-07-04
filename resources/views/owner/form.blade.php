@extends('layouts.main')

@section('css')
<style>
    .select2-results__option {
      white-space: normal;
    }
  </style>
@stop

@section('content')
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">

              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="{{ url('owner' . (!empty($models->id) ? '/' . $models->id : '')) }}" enctype="multipart/form-data">
              @csrf
              @if (!empty($models->id))
				@method('put')
			  @endif
                <div class="card-body">
                  <div class="form-group">
                    <label>Owner Category</label>
                    <select class="form-control" style="width: 100%;" id="owner_category_id" name="owner_category_id" required>
                      @if (!empty($owner_category))
                      @foreach ($owner_category as $oc)
                        <option value="{{ $oc->id }}" {{ isset($models) && $models->owner_category_id == $oc->id ? 'selected' : '' }}>{{ $oc->name }}</option>
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
                    <label>City</label>
                    <select class="form-control select2 select2-city" style="width: 100%;" id="city" name="city">
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
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ $models->email ?? old('email') }}"  autocomplete="email">
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
</script>
@stop