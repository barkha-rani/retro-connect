@extends('layouts.main')

@section('title', "Manage Retro Boards")

@section('content')
    <div class="pagetitle">
      <h1>Retro boards</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Retro boards</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            @if (Auth::user()->role == "scrumMaster")
              <div class="col-xxl-3 col-xl-12">
                <div class="tooltip-wrapper" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Create a new Retro Board">
                  <div class="card create-board" data-bs-toggle="modal" data-bs-target="#createBoardModal">
                    <div class="card-body">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-add-fill"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Create board -->
            @endif
            <input type="hidden" class="form-control" id="copyInput" style="opacity:0;">
            @foreach ($boards as $board)
                <div class="col-xxl-3 col-xl-12">
                    <div class="card board-card">
                        <form action="{{ route('boards.destroy', $board->uuid) }}" method="POST" class="deleteBoard" id="delete_{{ $board->uuid }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                {{-- <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                                </li> --}}
                                <li><a class="dropdown-item" href="#" onclick="copyTextToClipboard('{{ route('boards.show', $board->uuid) }}')"> <i class="ri-share-line"></i> Share</a></li>
                                @if (auth()->user()->role == "scrumMaster")
                                  <li onclick="deleteBoard('{{ $board->uuid }}')">
                                    <a class="dropdown-item" href="#">
                                      <i class="ri-delete-bin-3-line"></i>
                                      Delete
                                    </a>
                                  </li>
                                @endif
                            </ul>
                        </div>

                        <div class="card-body">
                            <a class="dropdown-item" href="{{ route('boards.show',[$board->uuid]) }}">                             
                              <h6 class="card-title">{{ $board->name }}</h6>
                            </a>
                            <span class="badge rounded-pill bg-light text-dark" style="font-weight:normal">
                                <i class="ri-time-line"></i> 
                                {{ $board->createdAtDisplayDate }}
                            </span>
                            <div class="card-categories">
                              @foreach ($board['columnsFinal'] as $key => $column)
                                <div 
                                  class="card-category {{ config('constants.colorDetails.'.$column['color'].'.bgCssClass') }}" 
                                  style="width:{{ ($column['percentile'] == 0)?10:$column['percentile'] }}%;" data-bs-toggle="tooltip" data-bs-placement="left" 
                                  data-bs-original-title="{{ $column['name']." (".$column['count'].")" }}"
                                ></div>
                              @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            

            {{-- <div class="col-xxl-3 col-xl-12">
              <div class="card board-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li><a class="dropdown-item" href="#"> <i class="ri-share-line"></i> Share</a></li>
                    <li><a class="dropdown-item" href="#"> <i class="ri-file-copy-line"></i> Clone</a></li>
                    <li><a class="dropdown-item" href="#"> <i class="ri-delete-bin-3-line"></i> Delete</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h6 class="card-title">Sprint 2 board</h6>
                  <span class="badge rounded-pill bg-light text-dark" style="font-weight:normal">
                    <i class="ri-time-line"></i> 
                    6 Sep 2023
                  </span>
                  <div class="card-categories">
                    <div class="card-category bg-primary" style="width:60%;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="What went well (6)"></div>
                    <div class="card-category bg-success" style="width:10%;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Thanks/Kudos (1)"></div>
                    <div class="card-category bg-warning" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="To Impprove (10)"></div>
                    <div class="card-category bg-info" style="width:20%;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Action Item (2)"></div>
                  </div>
                </div>
              </div>
            </div> --}}
          </div>
        </div><!-- End Left side columns -->
      </div>

      <form class="row g-3" method="POST" action="{{ route('boards.store') }}" id="createBoardForm">
          @csrf
          <div class="modal fade" id="createBoardModal" tabindex="-1" aria-hidden="true" style="display: none;">
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
                            required value="{{ old("name") }}">
                        <div class="invalid-feedback" id="nameError"></div>
                        @error('name')
                            <span class="invalid-feedback" id="nameError">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ old("description") }}</textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                        @error('description')
                            <span class="invalid-feedback" id="descriptionError">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 form-group" id="team_id_container">
                      <label for="team_id" class="form-label">Team Id</label>
                      <input id="team_id" type="text"
                          class="form-control @error('team_id') is-invalid @enderror" name="team_id"
                          value="{{ old('team_id') }}" required autocomplete="team_id" autofocus>
                      <span class="invalid-feedback" id="team_idError"></span>

                      @error('team_id')
                          <span class="invalid-feedback" id="team_idError">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary"><i class="ri-add-fill"></i> Create Board</button>
                </div>
              </div>
            </div>
          </div>
        </form>
    </section>
@endsection

@section("script")
  <script>
    function deleteBoard(uuid){
      var deleteBoardForm = $("#delete_" + uuid);
      console.log(deleteBoardForm);
      swal({
        title: "Are you sure, you want to delete this Board?",
        icon: "warning",
        buttons: {
          cancel: true,
          confirm: true,
        },
      }).then((value) => {
        if (value) {
          deleteBoardForm.submit();
        }
      });
    }

    function copyShareLink(url){
      {{-- var copyText = $("#copyInput");
      copyText.val(url);
      copyText.select();
      document.execCommand("copy"); --}}
      const copyContent = async () => {
        try {
          await navigator.clipboard.writeText(url);
          swal({
            icon: 'success',
            title: 'Link copied successfully',
            showConfirmButton: false,
            timer: 1500
          });
        } catch (err) {
          console.error('Failed to copy: ', err);
        }
      }
    }
    function fallbackCopyTextToClipboard(text) {
      var textArea = document.createElement("textarea");
      textArea.value = text;
      
      // Avoid scrolling to bottom
      textArea.style.top = "0";
      textArea.style.left = "0";
      textArea.style.position = "fixed";

      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();

      try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Fallback: Copying text command was ' + msg);
      } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
      }

      document.body.removeChild(textArea);
    }
    function copyTextToClipboard(text) {
      if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
      }
      navigator.clipboard.writeText(text).then(function() {
        swal({
          icon: 'success',
          title: 'Link copied successfully',
          showConfirmButton: false,
          timer: 1500
        });
      }, function(err) {
        console.error('Async: Could not copy text: ', err);
      });
    }
  </script>
@endsection