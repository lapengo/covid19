@extends('layout.headers')
@section('content')

<div class="row">

    <div class="col-xl-12">
        <canvas id="bar-chart" width="800" height="450"></canvas>
    </div>

    <div class="col-xl-12"> 
        <table  id="example" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
            <thead>
                <th>Negara</th>
                <th>Total Positif</th>
                <th>Total Meninggal</th>
                <th>Total Sembuh</th>
                {{-- <th>Active</th>  --}}
            </thead>
        </table>         
    </div>
</div> 

@stop

@push('scripts')
<script>
$(function() {
    $('#example').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ URL::to('corona/index') }}",
        columns: [
            { data: 'attributes.Country_Region' },
            { data: 'attributes.Confirmed' },
            { data: 'attributes.Deaths' },
            { data: 'attributes.Recovered' },
            // { data: 'attributes.Active' }
        ]
    });
});


new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["Total Positif", "Total Sembuh", "Total Meninggal"],
      datasets: [
        {
          label: "Population (millions)",
          backgroundColor: ["#3e95cd", "#27ae60", "#c0392b"],
          data: {{ $jml }}
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Total Keseluruhan Sebaran Covid19 Dunia'
      }
    }
});

</script>
@endpush 