@extends('layouts.boards')

@section('title', 'Manage Teams')

@section('content')
    <div class="pagetitle">
        <div class="board-title">
            <h1>{{ $board->name }}</h1>
            @if (Auth::id() == $board->user_id)
                <div class="edit-btn">
                    <i class="ri-settings-4-fill edit-board" data-bs-toggle="modal" data-bs-target="#editBoardModal"></i>
                </div>
            @endif
        </div>
        <small>{{ $board->description }}</small>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @foreach ($board->columns as $column)
                <div class="col-xxl-3">
                    <div class="row add-btn-parent">
                        <div class="col-xxl-12">
                            <h5 class="card-title">{{ $column->name }}</h5>
                        </div>
                        @if ($board->status == "draft" || $board->status == "ready")
                            <div class="col-xxl-12">
                                <button class="full-width-add-btn"><i class="ri-add-fill"></i></button>
                            </div>
                        @endif
                    </div>
                    <div class="row column-parent {{ $column->type }}">
                        <div class="col-xxl-12 col-xl-12 card-form-parent" style="display:none;">
                            <div class="card-textarea" style="border-color:{{ config('constants.colorDetails.' . $column->color . '.hex') }};">
                                <form action="#" class="add-card-form">
                                    @csrf
                                    <input type="hidden" name="column_id" value="{{ $column->uuid }}" />
                                    <textarea name="content" class="auto-resize-textarea" placeholder="Say something..."></textarea>
                                    <div class="buttons">
                                        <button type="submit" class="save-btn">Save</button>
                                        <button type="button" class="close-card"><i class="ri-close-line"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        @foreach ($column->cards as $card)
                            @php 
                                $board = App\Board::find($card->column->board_id); 
                            @endphp
                            <div class="col-xxl-12 col-xl-12">
                                <div
                                    class="card retro-card {{ config('constants.colorDetails.' . $column->color . '.bgCssClass') }}">
                                    @if ($board->status == "draft" || $board->status == "ready")
                                        @if (Auth::id() ==  $board->user_id || Auth::id() == $card->user_id)
                                            <div class="filter">
                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                        class="bi bi-three-dots-vertical"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    {{-- <li><a class="dropdown-item" href="#"> <i class=" ri-edit-2-line"></i>
                                                            Edit </a></li> --}}
                                                    {{-- <li><a class="dropdown-item" href="#"> <i class="ri-file-copy-line"></i>
                                                            Clone</a></li> --}}
                                                    @if (Auth::id() ==  $board->user_id || Auth::id() == $card->user_id)
                                                        @if (Auth::id() == $card->user_id || Auth::id() ==  $board->user_id)
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="deleteCard(event, '{{ $card->uuid }}')">
                                                                    <i class="ri-delete-bin-3-line"></i> 
                                                                    Delete
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($column->type == "toImprove")
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="moveToActionItem(event, '{{ $card->uuid }}')">
                                                                    <i class="ri-arrow-left-right-line"></i>
                                                                    Move to Action Item
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xxl-12">
                                                <p>{{ $card->content }}</p>
                                            </div>
                                            <div class="col-xxl-12 comment-card-footer">
                                                <div class="likes {{ ($board->status == "frozen" || $board->status == "archived")?"disabled":"" }}" data-id="{{ $card->uuid }}" data-liked="{{ Auth::user()->likedCards->contains($card)?"true":"false" }}">
                                                    @if (Auth::user()->likedCards->contains($card))
                                                        <i class="ri-heart-line" style="display:none;"></i>
                                                        <i class="ri-heart-fill"></i>
                                                    @else
                                                        <i class="ri-heart-line" ></i>
                                                        <i class="ri-heart-fill" style="display:none;"></i>
                                                    @endif
                                                    <span class="like-count" data-count="{{ count($card->likes) }}">{{ count($card->likes) }}</span>
                                                </div>
                                                <div class="author">
                                                    ~ {{ $card->author->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if (Auth::id() == $board->user_id)
            <form class="row g-3" method="POST" action="{{ route('boards.update', $board->uuid) }}" id="editBoardForm">
            @csrf
                <div class="modal fade" id="editBoardModal" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Board Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            required value="{{ $board->name }}">
                                        <div class="invalid-feedback" id="nameError"></div>
                                        @error('name')
                                            <span class="invalid-feedback" id="nameError">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $board->description }}</textarea>
                                        <div class="invalid-feedback" id="descriptionError"></div>
                                        @error('description')
                                            <span class="invalid-feedback" id="descriptionError">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" class="form-select @error('status') is-invalid @enderror"
                                            name="status" required>
                                            <option value="draft" {{ $board->status == "draft" ? 'selected' : '' }}>Draft</option>
                                            <option value="ready" {{ $board->status == "ready" ? 'selected' : '' }}>Ready</option>
                                            <option value="frozen" {{ $board->status == "frozen" ? 'selected' : '' }}>Frozen</option>
                                            <option value="archived" {{ $board->status == "archived" ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"><i class="ri-add-fill"></i> Update Board</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.auto-resize-textarea').on('input', function () {
                $(this).css('height', 'auto');
                $(this).css('height', (this.scrollHeight) + 'px');
            });

            $(document).on('click', '.close-card', function(){
                $(this).parent().siblings('textarea').val('');
                $(this).parent().siblings('textarea').height('31px');
                $(this).closest('.card-form-parent').hide();
            });

            $(document).on('click', '.full-width-add-btn', function(){
                $(this).closest('.add-btn-parent').next().children('.card-form-parent').show();
            });

           $(document).on('click', '.likes', function(){
                var data = {
                    '_token': '{{ csrf_token() }}',
                    'card_id': $(this).data('id'),
                    'status': ($(this).data("liked") == true)?"liked":"disliked",
                }
                var likes = $(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('cards.like') }}",
                    data: data,
                    success: function (response) {
                        currentCount = likes.children('.like-count').data('count');
                        if(response.data == "liked"){
                            currentCount += 1;
                            likes.children('.ri-heart-fill').show();
                            likes.children('.ri-heart-line').hide();
                        }else{
                            currentCount -= 1;
                            likes.children('.ri-heart-fill').hide();
                            likes.children('.ri-heart-line').show();
                        }
                        likes.children('.like-count').data('count', currentCount);
                        likes.children('.like-count').html(currentCount);
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response here
                        console.log('Error:', xhr.responseText);
                    }
                });
            });

            $('.add-card-form').on('submit', function (e) {
                e.preventDefault(); 
                form = $(this);
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('cards.store') }}",
                    data: formData,
                    success: function (response) {
                        var cardHtml = getCardHtml(response.data);
                        form.closest(".column-parent").append(cardHtml);
                        form.find("textarea").val('');
                        form.find("textarea").height('31px');
                        form.closest(".card-form-parent").hide();
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response here
                        console.log('Error:', xhr.responseText);
                    }
                });
            });
        });

        function deleteCard(event, card_id){
            swal({
                title: "Do you really want to delete this card?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
            }).then((value) => {
                if (value) {
                    var data = {
                        '_token': '{{ csrf_token() }}',
                        'card_id': card_id,
                    }
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('cards.destroy') }}",
                        data: data,
                        success: function (response) {
                            if(response.status == "success"){
                                $(event.target).closest('.retro-card').parent().remove();
                            }else{
                                console.log("Something went wrong, Please try again.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Error:', xhr.responseText);
                        }
                    });
                }
            });
        }

        function moveToActionItem(event, card_id){
            swal({
                title: "Are you sure, you want to move this card to Action Items?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
            }).then((value) => {
                if (value) {
                    var data = {
                        '_token': '{{ csrf_token() }}',
                        'card_id': card_id,
                    }
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('cards.move') }}",
                        data: data,
                        success: function (response) {
                            if(response.status == "success"){
                                $(event.target).closest('.retro-card').parent().remove();
                                cardHtml = getCardHtml(response.data);
                                $(".column-parent.actionItem").append(cardHtml);
                            }else{
                                console.log("Something went wrong, Please try again.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Error:', xhr.responseText);
                        }
                    });
                }
            });
        }

        function getCardHtml(card){
            var moveHtml = `<li>
                                <a class="dropdown-item" href="#" onclick="moveToActionItem(event, '${card.uuid}')">
                                    <i class="ri-arrow-left-right-line"></i>
                                    Move to Action Item
                                </a>
                            </li>`;
            return `
                <div class="col-xxl-12 col-xl-12">
                    <div
                        class="card retro-card ${colorDetails[card.color].bgCssClass}">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots-vertical"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="deleteCard(event, '${card.uuid}')"> 
                                        <i class="ri-delete-bin-3-line"></i> 
                                        Delete
                                    </a>
                                </li>
                                ${(card.type == 'toImprove') ? moveHtml : ''}
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-12">
                                    <p>${card.content}</p>
                                </div>
                                <div class="col-xxl-12 comment-card-footer">
                                    <div class="likes" data-id="${card.uuid}" data-liked='false'>
                                        ${(card.liked)
                                            ?"<i class='ri-heart-line' style='display:none;'></i><i class='ri-heart-fill'></i>"
                                            :"<i class='ri-heart-line'></i><i class='ri-heart-fill' style='display:none;'></i>"
                                        }
                                        <span class="like-count" data-count="${(card.likes_count?card.likes_count:"0")}">${(card.likes_count?card.likes_count:"0")}</span>
                                    </div>
                                    <div class="author">
                                        ~ ${card.author_name}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `
        }
    </script>
@endsection