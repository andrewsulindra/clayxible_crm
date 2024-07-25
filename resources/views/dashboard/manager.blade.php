@extends('layouts.main')


@section('css')
<style>
  .hidden-div {
    display: none;
  }
  .pl-30 {
    padding-left: 30px;
  }
</style>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @php
            $projectStatuses = [
                'OPEN' => 1,
                'CONFIRM' => 2,
                'FOLLOW_UP' => 3,
                'NEED_FOLLOW_UP' => 4,
                'CUT' => 5,
                'CLOSED' => 6,
            ];

            $cardClasses = [
                'OPEN' => 'card-success',
                'CONFIRM' => 'card-primary',
                'FOLLOW_UP' => 'card-warning',
                'NEED_FOLLOW_UP' => 'card-warning',
                'CUT' => 'card-danger',
                'CLOSED' => 'card-success',
            ];
        @endphp


        @foreach ($projectStatuses as $statusLabel => $statusValue)
        @php
            $cardClass = $cardClasses[$statusLabel];
            $collapsedClass = $statusLabel === 'OPEN' ? '' : 'collapsed-card';
        @endphp
        <div class="card {{ $cardClass }} {{ $collapsedClass }}">
          <div class="card-header">
            <h3 class="card-title">{{ $statusLabel }}</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <ul class="nav nav-pills flex-column">
              @foreach ($data_dashboard as $dd)
              <li class="nav-item">
                @if(isset($dd['sales'][$statusValue]))
                    <text class="nav-link">
                        @foreach ($dd['sales'][$statusValue] as $ds)
                            {{ $ds['name'] }}
                            <span class="float-right">
                                {{ $ds['count'] }}
                            </span>
                        @endforeach
                    </text>
                @endif
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        @endforeach
        <!-- /.card-body -->
      </div>
      <pre><?php echo json_encode($data_dashboard, JSON_PRETTY_PRINT) ?></pre>

      </div><!--/. container-fluid -->
</section>
<!-- /.content -->
@stop

@section('js')
<script>
document.querySelectorAll('.toggleLink').forEach(link => {
  link.addEventListener('click', function(event) {
    event.preventDefault();
    var toggleDiv = this.nextElementSibling;
    if (toggleDiv.style.display === "none" || toggleDiv.style.display === "") {
      toggleDiv.style.display = "block";
    } else {
      toggleDiv.style.display = "none";
    }
  });
});
</script>
@stop