<script>
    window.onload=function() {
        @if (session('log'))
            alert("{{session('log')}}");
        @endif
    }
</script>

