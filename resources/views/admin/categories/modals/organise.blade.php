<div class="modal fade" tabindex="-1" id="categories_organise" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Organize categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.categories.organize')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <ul class="list-group" id="categories_list">
                        @foreach($categories as $category)
                            <li class="list-group-item">
                                <input type="hidden" name="categories[]" value="{{$category->id}}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cursor-move handle">
                                        <i class="fa-solid fa-bars"></i>
                                    </div>
                                    <i class="fa-solid fa-{{$category->icon}}"></i>
                                    <div>{{$category->title}}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('categories_organise').addEventListener('show.bs.modal', event => {
       Sortable.create(document.getElementById('categories_list'), {
            ghostClass: "bg-light-dark",
            handle: ".handle"
        });
    })
</script>
