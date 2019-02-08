<ol class="breadcrumb float-right">
    @for($i = 1; $i <= count(Request::segments()); $i++)
        @if (!((count(Request::segments()) == 3) && ($i == 2)))
            <li class="breadcrumb-item d">
                @if ($i < count(Request::segments()) & $i > 0)
                    <?php $link = "/" . Request::segment($i); ?>
                    <a href="<?= $link ?>">{{ucwords(Request::segment($i))}}</a>
                @else
                    {{ucwords(Request::segment($i))}}
                @endif
            </li>
        @endif
    @endfor
</ol>
