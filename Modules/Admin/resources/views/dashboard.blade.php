@extends('admin::layouts.navigation')

@section('content')

    <div class="flex flex-wrap justify-center mt-10">
        <div class="h-40 sm:h-96 w-auto sm:w-[40rem] md:w-[45rem] mb-10">
            <canvas id="monthlyOrder" class="pr-0"></canvas>
        </div>

        <div class="h-40 sm:h-96 w-auto sm:w-[40rem] md:w-[45rem] mb-10">
            <canvas id="monthlyIncome"></canvas>
        </div>
    </div>
@endsection