@section('content')
    @if (session('user'))
        @if (session('user') and (session('user')->role=='A'))
            @include("verification.verificationAdmin", ['data'=>$data])
        @endif
        @if (session('user') and (session('user')->role!='A'))
            @include("verification.verificationUser")
        @endif
    @endif
@endsection
@include('base')