@extends('layouts.app')

@section('title', 'Generate Payslips')

@section('content')
<div class="pagetitle">
    <h1>Generate Payslips</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payslips.index') }}">Payslips</a></li>
            <li class="breadcrumb-item active">Generate</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Batch Generate Payslips</h5>
                    <p>Enter the period (e.g. <code>10-2023</code>) to generate payslips for all active employees.</p>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('payslips.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="period" class="col-sm-3 col-form-label">Period</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="period" name="period" placeholder="MM-YYYY" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Generate</button>
                            <a href="{{ route('payslips.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
