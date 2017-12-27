<?php
$path = '';
switch (session('user.role')) {
    case 'A':
        $path = 'admin';
        break;
    case 'B':
        $path = 'business';
        break;
    case 'C':
        $path = 'customer';
        break;
}
?><div id="menu">
    <section class="menu-section">
        <h5>會員</h5>
        <div class="list-group">
            <a class="list-group-item list-group-item-action"
               href="{{action('UserController@getUserInfo')}}"
            >會員資料</a>
            @if($path!=='admin')
            <a class="list-group-item list-group-item-action"
               href="{{action('VerificationController@getVerification')}}"
            >會員驗證</a>
            @endif
        </div>
    </section>

    @if(!empty($path))
        @include('management.menu.'.$path)
    @endif
    <script type="text/javascript">
        (function () {
            let menu = $("#menu");
            let items = menu.find('.list-group-item');
            items.each(function () {
                this.title = this.textContent;
            });

            function setCurrentSelect(name) {
                let activeClass = 'active border-warning amber';
                items.removeClass(activeClass);

                items.filter('[title="' + name + '"]').addClass(activeClass);
            }

            setCurrentSelect('{{$title or ''}}');
        })();

    </script>
</div>
