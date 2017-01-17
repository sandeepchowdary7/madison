@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ $document->title }}</h1>
    </div>

    @include('components.errors')

    <div class="row">
        <div class="col-md-12">
            <p>
                <div class="btn-group" role="group">
                        {{ Form::open(['route' => ['documents.support', $document->slug], 'method' => 'put']) }}
                            <input type="hidden" name="support" value="1">

                            @if ($userSupport === true)
                                <button type="submit" class="btn btn-success">{{ trans('messages.document.supported') }}</button>
                            @else
                                <button type="submit" class="btn btn-default">{{ trans('messages.document.support') }}</button>
                            @endif
                        {{ Form::close() }}
                </div>
                <div class="btn-group" role="group">
                        {{ Form::open(['route' => ['documents.support', $document->slug], 'method' => 'put']) }}
                            <input type="hidden" name="support" value="0">
                            @if ($userSupport === false)
                                <button type="submit" class="btn btn-warning">{{ trans('messages.document.opposed') }}</button>
                            @else
                                <button type="submit" class="btn btn-default">{{ trans('messages.document.oppose') }}</button>
                            @endif
                        {{ Form::close() }}
                </div>
            </p>
        </div>
    </div>

    @if (!empty($document->introtext))
        <div class="panel panel-default">
            <div class="panel-heading">@lang('messages.document.introtext')</div>
            <div class="panel-body">
                {!! $document->introtext !!}
            </div>
        </div>
    @endif

    <div class="row">
        <section id="page_content" class="col-md-8">
            {!! $pages->first()->rendered() !!}
        </section>

        <aside class="annotation-container col-md-4">
            <h2>@lang('messages.document.notes')</h2>
        </aside>
    </div>

    {{ $pages->appends(request()->query())->fragment('page_content')->links() }}

    @push('scripts')
        <script src="{{ elixir('js/annotator-madison.js') }}"></script>
        <script src="{{ elixir('js/document.js') }}"></script>
        <script>
            loadTranslations([
                'messages.close',
                'messages.document.add_reply',
                'messages.document.collaborators_count',
                'messages.document.note',
                'messages.document.note_edit_explanation_prompt',
                'messages.document.notes',
                'messages.document.note_reply',
                'messages.document.replies_count',
                'messages.edit',
                'messages.none',
                'messages.submit'
            ])
            .done(function () {
                loadAnnotations(
                    "#page_content",
                    ".annotation-container",
                    {{ $document->id }},
                    {{ request()->user() ? request()->user()->id : 'null' }},
                    {{ $document->discussionState === \App\Models\Doc::DISCUSSION_STATE_CLOSED ? 1 : 0 }}
                );
        });
        </script>
    @endpush
@endsection
