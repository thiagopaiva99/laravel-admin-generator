@if (session()->has('flash_notification.message'))
    @if (session()->has('flash_notification.overlay'))
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => session('flash_notification.title'),
            'body'       => session('flash_notification.message')
        ])
    @else
        <div class="row">
            <div class="col s12 m12" style="width: 100%; color: #fff; border-radius: 10px;">
                <div class="alert {{ session()->has('flash_notification.important') ? 'alert-important' : '' }}" style="background-color: #616161">
                    @if(session()->has('flash_notification.important'))
                    {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>--}}
                    @endif
                    {!! session('flash_notification.message') !!}
                </div>
            </div>
        </div>
    @endif
@endif

{{--{{ session('flash_notification.level') }} {{ session()->has('flash_notification.important') ? 'alert-important' : '' }}--}}