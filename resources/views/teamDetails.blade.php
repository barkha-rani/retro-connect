@extends('layouts.main')

@section('title', "Manage Teams")

@section('content')
    <div class="pagetitle">
      <h1>Team Details</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route("teams") }}">Manage Teams</a></li>
          <li class="breadcrumb-item active">Details</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-table-header">
                            <h5 class="card-title">{{ $team->name }}</h5>
                            <div class="action-buttons">
                              <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#inviteUsersModal"> <i class="ri-share-fill"></i> </button>
                              @if (Auth::user()->role == "scrumMaster")
                                <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#updateTeamModal"> <i class="ri-edit-2-fill"></i> </button>

                                <form style="display: inline-block;" action="{{ route('teams.destroy', $team->uuid) }}" method="POST" id="teamDeleteButton">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill"> <i class="ri-delete-bin-2-fill"></i> </button>
                                </form>
                              @endif
                            </div>
                        </div>
                        <!-- Table with hoverable rows -->
                        <div class="row">
                          <div class="col-xxl-12 pb-2">
                            <div class="text-label">Description</div>
                          </div>
                          <div class="col-xxl-11 small">
                            <p>{{ $team->description }}</p>
                          </div>
                        </div>
                        <h5 class="card-title">Team Members:</h5>
                        @if (count($team->members) > 0)
                          <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Name</th>
                                  <th scope="col">E-mail</th>
                                  <th scope="col">Joining date</th>
                                </tr>
                              </thead>
                              <tbody>
                                @php $i = 1 @endphp
                                @foreach ($team->members as $teamMember)
                                  <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $teamMember->name }}</td>
                                    <td>{{ $teamMember->email }}</td>
                                    <td>{{ $teamMember->created_at }}</td>
                                  </tr>
                                @endforeach
                              </tbody>
                          </table>
                        @else
                          <strong>No Members joined yet</strong>
                        @endif
                    </div>
                </div>
            </div>
          </div>
        </div><!-- End Left side columns -->

        <!-- End Right side columns -->
        <form class="row g-3" method="POST" action="{{ route('teams.update', $team->uuid) }}" id="updateTeamForm">
          @csrf
          @method('PUT')
          <div class="modal fade" id="updateTeamModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Team Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-12 form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" id="name"
                            required value="{{ $team->name }}">
                        <div class="invalid-feedback" id="nameError"></div>
                        @error('name')
                            <span class="invalid-feedback" id="nameError">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $team->description }}</textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                        @error('description')
                            <span class="invalid-feedback" id="descriptionError">{{ $message }}</span>
                        @enderror
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary"><i class="ri-add-fill"></i> Update</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="modal fade" id="inviteUsersModal" tabindex="-1" aria-hidden="true" style="display: none;">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Share this link to invite members for this team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3">
                  <div class="col-12">
                    <div class="input-group">
                      <input type="text" class="form-control" id="copyInput" value="{{ route('register') }}?team_id={{ $team->uuid }}" readonly>
                      <div class="input-group-append">
                        <button class="btn btn-primary" id="copyButton">
                          <i class="ri-clipboard-line"></i> Copy
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    
    $("#copyButton").click(function() {
      var copyText = $("#copyInput");
      copyText.select();
      document.execCommand("copy");
      swal({
        icon: 'success',
        title: 'Link copied successfully',
        showConfirmButton: false,
        timer: 1500 // Automatically close the popup after 1.5 seconds
      });
      // Close the modal
      $('#inviteUsersModal').modal('hide');
    });

    $("#teamDeleteButton").on("submit", function(event) {
      event.preventDefault();
      swal({
        title: "Do you really want to delete this Team?",
        buttons: {
          cancel: true,
          confirm: true,
        },
      }).then((value) => {
        if (value) {
          // If the user clicks "Confirm," submit the form
          $(this).off("submit").submit();
        }
      });
    });
  });
</script>
@endsection