<script>
    window.onload = function () {
        <?php
        $log = session('log') ?? null;
        $message = '';
        $type = 'info';
        if (is_string($log)) {
            $message = $log;
        } elseif (is_array($log)) {
            $allowType = explode(' ', 'info warning success error');
            if (in_array($log['type'] ?? '', $allowType))
                $type = $log['type'];
        }
        ?>
        @if(!empty($log) && !empty($type))
            toastr['{{$type}}']({!! json_encode($message) !!});
        @endif
    };
    @if(!session('user') || session('refreshKey'))
    sessionStorage.clear();
    @endif
</script>

