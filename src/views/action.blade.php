<div class="actions" style="display:flex;">
    <a title="edit" href="{{ route(preg_replace('/\.index/', '.edit', app('router')->getCurrentRoute()->getName()), $id) }}" class="btn btn-sm btn-action-datatables"><i class="fa fa-edit"></i></a>
    @if(isset($active) && 0 === (int)$active)
        <a title="activate" href="{{ route(preg_replace('/\.index/', '.activate', app('router')->getCurrentRoute()->getName()), $id) }}" class="btn btn-sm btn-action-datatables"><i class="fa fa-check-square-o"></i></a>
    @endif
    @if(isset($active) && 1 === (int)$active)
        <a title="deactivate" href="{{ route(preg_replace('/\.index/', '.deactivate', app('router')->getCurrentRoute()->getName()), $id) }}" class="btn btn-sm btn-action-datatables"><i class="fa fa-check-square"></i></a>
    @endif
    <a title="delete">
        <form action="{{ route(preg_replace('/\.index/', '.destroy', app('router')->getCurrentRoute()->getName()), $id) }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="delete">
            <button type="submit" class="btn btn-sm btn-action-datatables">
                <i class="fa fa-remove"></i>
            </button>
        </form>
    </a>
</div>
