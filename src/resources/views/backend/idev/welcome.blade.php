@extends("backend.parent")
@section("content")
@push('mtitle')
{{$title}}
@endpush
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">Data per tanggal 10 Desember 2022.</p>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Total AUM</h5>
                        <label for=""></label>
                    </div>
                    <div class="card-body">
                        
                        <h1>{{ $totalAum }}</h1>  
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Total Placement</h5>
                        <label for="">Approved/Total</label>
                    </div>
                    <div class="card-body">
                        <h1>{{ $accPlacement }}/{{ $totalPlacement }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Total Pinjaman</h5>
                        <label for="">Approved/Total</label>
                    </div>
                    <div class="card-body">
                        <h1>{{ $accLoan }}/{{ $totalLoan }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Menunggu Approval Placement</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama AUM</th>
                                    <th>Jumlah Pinjam</th>
                                    <th>Keperluan</th>
                                    <th>Waktu Pengajuan</th>
                                </tr>
                            </thead>
                            @foreach($menungguPlacement as $p)
                            <tbody>
                                <tr>
                                    <th>1</th>
                                    <th>{{ $p->code }}</th>
                                    <th>Rp 100.000.000</th>
                                    <th>Memperluas Cabang</th>
                                    <th>1 days ago</th>
                                </tr>
                            </tbody>
                            @endforeach                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Menunggu Approval Pinjaman</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>User</th>
                                    <th>Aum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @foreach($menungguLoan as $l)
                            <tbody>
                                <tr>
                                    <th>{{ $l->id }}</th>
                                    <th>{{ $l->code }}</th>
                                    <th>{{ $l->description }}</th>
                                    <th>{{ $l->nameuser }}</th>
                                    <th>{{ $l->nameaum }}</th>
                                    <th>{{ $l->txt_accepted }}</th>
                                </tr>                     
                            </tbody>
                            @endforeach 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@push('scripts')
<script>
  $(document).ready(function() {
    console.log("welcome console");
  })
</script>
@endpush
@endsection