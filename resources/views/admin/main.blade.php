@include('admin._parts.header')
@include('admin._parts.header_top')
@include('admin._parts.navbar')
@include('admin._parts.toast')

<div class="content">
    @if(isset($page_name))
        @include($page_name)
    @else
        <p>Invalid page name</p>
    @endif
</div>
@include('admin._parts.modal')
@include('admin._parts.footer_include')
@include('admin._parts.footer')
