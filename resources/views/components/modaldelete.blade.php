{{-- modal confirm delete --}}
<div id="confirmDelete" class="ui modal">
  <i class="close icon"></i>
  <div class="header">
    Confirm Delete
  </div>
  <div class="content">
    <div id="confirmMessage" class="description">
      <p>{{$idToDelete}}</p>
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Cancel
    </div>
    <div class="ui positive right labeled icon button" wire:click="destroy({{$idToDelete}})">
      Yes, delete
      <i class="trash icon"></i>
    </div>
  </div>
</div>