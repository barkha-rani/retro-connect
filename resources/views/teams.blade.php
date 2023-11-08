@extends('layouts.main')

@section('title', "Manage Teams")

@section('content')
    <div class="pagetitle">
      <h1>Manage Teams</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Manage Teams</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Create board -->
            {{-- <div class="col-xxl-3 col-xl-12">
              <div class="tooltip-wrapper" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Create a new Retro Board">
                <div class="card create-board" data-bs-toggle="modal" data-bs-target="#createBoardModal">
                  <div class="card-body">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-add-fill"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> --}}
            <!-- End Create board -->

            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-table-header">
                            <h5 class="card-title">Your Teams</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeamModal"><i class="ri-add-fill"></i> Create new team</button>
                        </div>
                        <!-- Table with hoverable rows -->
                        <table class="table table-hover">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Team ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Members count</th>
                                <th scope="col">Created On</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>
                            <input type="text" class="form-control" id="copyInput" style="width:0;width: 0;border: 0;height:0 ">
                              @foreach ($teams as $key => $team)
                                <tr>
                                    <th scope="row">{{ $key+1 }}</th>
                                    <td><a href="{{ route("teams.show", [$team->uuid]) }}">{{ $team->uuid }}</a></td>
                                    <td>{{ $team->name }}</td>
                                    <td>
                                      @if (strlen($team->description) > 30)
                                          {{ substr($team->description, 0, 30) }}...
                                      @else
                                          {{ $team->description }}
                                      @endif
                                    </td>
                                    <td>{{ count($team->members) }}</td>
                                    <td>{{ $team->created_at }}</td>
                                    <td>
                                      <form action="{{ route('teams.destroy', $team->uuid) }}" method="POST" class="deleteTeam" id="delete_{{ $team->uuid }}">
                                          @csrf
                                          @method('DELETE')
                                          {{-- <button style="" type="submit" class="btn btn-danger btn-sm rounded-pill"> <i class="ri-delete-bin-2-fill"></i> </button> --}}
                                      </form>
                                      <div class="filter" style="position:relative !important; top:0;">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                          <li data-bs-toggle="modal">
                                            <a class="dropdown-item" href="#" onclick="copyTextToClipboard('{{ route('register').'?team_id='.$team->uuid }}')"><i class="ri-link"></i> Invite Link</a>
                                          </li>
                                          <li data-bs-toggle="modal" data-bs-target="#addMemberModal" onclick="updateTeamId('{{ $team->uuid }}')"><a class="dropdown-item"> <i class="ri-user-add-line"></i> Add Member</a></li>
                                          <li><a class="dropdown-item" href="#" onclick="deleteTeam('{{ $team->uuid }}');"> <i class="ri-delete-bin-3-line"></i> Delete</a></li>
                                        </ul>
                                      </div>
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div><!-- End Left side columns -->

        <!-- End Right side columns -->
        <form class="row g-3" method="POST" action="{{ route('teams.store') }}" id="createTeamForm">
          @csrf
          <div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add Team Details</h5>
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
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary"><i class="ri-add-fill"></i> Create New Team</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <form class="row g-3" method="POST" action="{{ route('teams.addMember') }}" id="addMemberForm">
          @csrf
          <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add Team Member</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <input type="hidden" name="team_id" value="" id="team_id" />
                    <div class="col-12 form-group">
                        <label for="email" class="form-label">User Email</label>
                        <input type="text" name="email"
                            class="form-control @error('email') is-invalid @enderror" id="email"
                            required>
                        <div class="invalid-feedback" id="emailError"></div>
                        @error('email')
                            <span class="invalid-feedback" id="emailError">{{ $message }}</span>
                        @enderror
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary"><i class="ri-add-fill"></i>Add Team Member</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="modal fade" id="inviteUsersModal" tabindex="-1" aria-hidden="true" style="display: none;">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Invitee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3">
                  <div class="col-12 form-group">
                    <div class="label-container">
                      <label for="emails" class="form-label">Enter Emails</label>
                      <i class="ri-information-fill" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Enter coma separated emails."></i>
                    </div>
                    <textarea type="text" name="emails" class="form-control @error('emails') is-invalid @enderror" id="emails">{{ old("emails") }}</textarea>
                    <div class="invalid-feedback" id="emailsError"></div>
                    @error('emails')
                        <span class="invalid-feedback" id="emailsError">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="ri-user-add-line"></i> Invite Team Members</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@section("script")
  <script>
    function deleteTeam(uuid){
      var deleteTeamForm = $("#delete_" + uuid);
      console.log(deleteTeamForm);
      swal({
        title: "Do you really want to delete this Team?",
        icon: "warning",
        buttons: {
          cancel: true,
          confirm: true,
        },
      }).then((value) => {
        if (value) {
          deleteTeamForm.submit();
        }
      });
    }

    {{-- function copyShareLink(url){
      var copyText = $("#copyInput");
      copyText.val(url);
      copyText.select();
      document.execCommand("copy");
      swal({
        icon: 'success',
        title: 'Link copied successfully',
        showConfirmButton: false,
        timer: 1500 // Automatically close the popup after 1.5 seconds
      });
    } --}}

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

    function updateTeamId(uuid){
      $("#team_id").val(uuid);
    }
  </script>
@endsection